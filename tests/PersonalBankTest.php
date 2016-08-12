<?php

require_once dirname(__FILE__) . '/../PersonalBank.php';

/**
 * 測試銀行功能
 */
class PersonalBankTest extends PHPUnit_Extensions_Database_TestCase
{
    private $pdo;

    public function setUp()
    {
        $pdo = new PDO('sqlite::memory:');

        $stmt = $pdo->prepare('CREATE TABLE account (id int, username varchar(10), balance int, version int)');
        $stmt->execute();

        $stmt = $pdo->prepare('CREATE TABLE detail (id int, account_id int, amount int, balance int, created_at datetime)');
        $stmt->execute();

        DB::$db = $pdo;

        parent::setUp();
    }

    /**
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection()
    {
        if (!isset(DB::$db)) {
            throw Exception('測試資料庫尚未準備就緒');
        }

        return $this->createDefaultDBConnection(DB::$db, ':memory:');
    }

    /**
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet()
    {
        return new PHPUnit_Extensions_Database_DataSet_YamlDataSet(
            dirname(__FILE__) . '/LoadPersonalBankData.yml'
        );
    }

    /**
     * 測試取得餘額
     */
    public function testGetBalance()
    {
        $bank = new PersonalBank(1);

        $this->assertEquals(0, $bank->getBalance());
    }

    /**
     * 測試回傳全部明細
     */
    public function testGetAllDetail()
    {
        $bank = new PersonalBank(1);
        $details = $bank->getAllDetail();

        $this->assertCount(2, $details);

        $this->assertEquals(1, $details[0]['id']);
        $this->assertEquals(1, $details[0]['account_id']);
        $this->assertEquals(10, $details[0]['amount']);
        $this->assertEquals(10, $details[0]['balance']);
        $this->assertEquals('2010-04-24 17:15:23', $details[0]['created_at']);

        $this->assertEquals(2, $details[1]['id']);
        $this->assertEquals(1, $details[1]['account_id']);
        $this->assertEquals(-10, $details[1]['amount']);
        $this->assertEquals(0, $details[1]['balance']);
        $this->assertEquals('2010-04-24 17:16:23', $details[1]['created_at']);
    }

    /**
     * 測試出入款
     */
    public function testDeposit()
    {
        $bank = new PersonalBank(1);

        $balance = $bank->deposit(10);
        $this->assertEquals(10, $balance);

        $balance = $bank->deposit(-8);
        $this->assertEquals(2, $balance);
    }

    /**
     * 測試出款，但餘額不足
     */
    public function testDepositWithNoEnoughBalance()
    {
        $this->expectOutputString('ERROR: 餘額不足');

        $bank = new PersonalBank(1);
        $bank->deposit(-9);
    }
}
