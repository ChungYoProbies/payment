<?php

require_once 'Account.php';
require_once 'Detail.php';

/**
 * 個人銀行
 */
class PersonalBank
{
    /**
     * 使用者帳戶
     *
     * @var Account
     */
    private $account;

    /**
     * 使用者交易明細
     *
     * @var Detail
     */
    private $detail;

    /**
     * 初始化
     *
     * @param integer $accountId 帳戶編號
     */
    public function __construct($accountId)
    {
        $this->account = new Account($accountId);
        $this->detail = new Detail($accountId);
    }

    /**
     * 回傳餘額
     *
     * @return integer
     */
    public function getBalance()
    {
        return $this->account->getBalance();
    }

    /**
     * 執行出入款
     *
     * @param integer $amount 交易金額
     * @return integer
     */
    public function deposit($amount)
    {
        $db = DB::getInstance();

        try {
            $db->beginTransaction();

            $newBalance = $this->account->updateBalance($amount);
            $this->detail->create($amount, $newBalance);

            $db->commit();

            return $newBalance;
        } catch (Exception $e) {
            $db->rollback();

            echo 'ERROR: ', $e->getMessage();
        }
    }

    /**
     * 取得全部交易明細
     *
     * @return array
     */
    public function getAllDetail()
    {
        return $this->detail->getList();
    }
}
