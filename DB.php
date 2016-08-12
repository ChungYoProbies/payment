<?php

/**
 * 資料庫
 */
class DB
{
    /**
     * PDO 連線
     *
     * @var PDO
     */
    private static $db;

    /**
     * 避免 DB 被初始化
     */
    private function __construct()
    {
    }

    /**
     * 取得單一 DB 連線資訊
     *
     * @return PDO
     */
    public static function getInstance()
    {
        if (self::$db) {
            return self::$db;
        }

        $dsn = 'mysql:dbname=db_newbie;host=127.0.0.1';
        $user = 'root';
        $password = '6881703';

        self::$db = new PDO($dsn, $user, $password);

        return self::$db;
    }
}
