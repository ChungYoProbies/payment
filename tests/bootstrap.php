<?php

/**
 * 測試用的資料庫
 */
class DB
{
    /**
     * PDO 連線
     *
     * @var PDO
     */
    public static $db;

    /**
     * 取得單一 DB 連線資訊
     *
     * @return PDO
     */
    public static function getInstance()
    {
        return self::$db;
    }
}
