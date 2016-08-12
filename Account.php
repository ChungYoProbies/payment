<?php

/**
 * 使用者帳戶
 */
class Account
{
    /**
     * 帳戶編號
     *
     * @var integer
     */
    private $id;

    /**
     * 初始化帳戶
     *
     * @param integer $id 帳戶編號
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * 取得使用者餘額
     *
     * @return integer
     */
    public function getBalance()
    {
        $db = DB::getInstance();

        $stmt = $db->prepare('SELECT `balance` FROM `account` WHERE `id` = ?');
        $stmt->execute([$this->id]);
        $ret = $stmt->fetch();

        return $ret['balance'];
    }

    /**
     * 更新餘額
     *
     * @param integer $amount 交易金額
     * @return integer
     */
    public function updateBalance($amount)
    {
        $db = DB::getInstance();

        $stmt = $db->prepare('SELECT * FROM `account` WHERE `id` = ?');
        $stmt->execute([$this->id]);
        $account = $stmt->fetch();

        $newBalance = $account['balance'] + $amount;
        $version = $account['version'];

        if ($newBalance < 0) {
            throw new RuntimeException('餘額不足');
        }

        $sql = 'UPDATE `account` SET' .
            ' `balance` = `balance` + ?, `version` = `version` + 1' .
            ' WHERE `id` = ? AND `version` = ?';
        $stmt = $db->prepare($sql);
        $stmt->execute([$amount, $this->id, $version]);

        if ($stmt->rowCount() != 1) {
            throw new RuntimeException('資料庫忙碌中');
        }

        return $newBalance;
    }
}
