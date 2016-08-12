<?php

/**
 * 交易明細
 */
class Detail
{
    /**
     * 帳戶編號
     *
     * @var integer
     */
    private $accountId;

    /**
     * 初始化
     *
     * @param integer $accountId 帳戶編號
     */
    public function __construct($accountId)
    {
        $this->accountId = $accountId;
    }

    /**
     * 取得交易明細列表
     *
     * @return array
     */
    public function getList()
    {
        $db = DB::getInstance();

        $stmt = $db->prepare('SELECT * FROM `detail` WHERE `account_id` = ?');
        $stmt->execute([$this->accountId]);

        return $stmt->fetchAll();
    }

    /**
     * 建立一筆交易明細
     *
     * @param integer $amount 交易金額
     * @param integer $balance 餘額
     * @return integer
     */
    public function create($amount, $balance)
    {
        $db = DB::getInstance();

        $stmt = $db->prepare('INSERT INTO `detail` VALUES (0,?,?,?,?)');
        $stmt->execute([
            $this->accountId,
            $amount,
            $balance,
            date('Y-m-d H:i:s')
        ]);

        return $db->lastInsertId();
    }
}
