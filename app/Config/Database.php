<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Config;

use PDO;

class Database
{
    private static ?\PDO $pdo = null;
    public static function getConnection(string $env = "test"): \PDO
    {
        // Singleton architecture (User single to many action)
        // if pdo null then create new db if not .. just return back pdo had been created
        if (self::$pdo == null) {
            // create new database
            require_once __DIR__ . "/../../env/database.php";
            $config = getDataConfig();
            self::$pdo = new PDO(
                $config["database"][$env]["url"],
                $config["database"][$env]["username"],
                $config["database"][$env]["password"]
            );
        }
        return self::$pdo;
    }

    public static function beginTransaction()
    {
        self::$pdo->beginTransaction();
    }
    public static function commitTransaction()
    {
        self::$pdo->commit();
    }
    public static function rollbackTransaction()
    {
        self::$pdo->rollBack();
    }
}
