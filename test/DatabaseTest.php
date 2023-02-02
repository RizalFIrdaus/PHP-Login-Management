<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;

class DatabaseTest extends TestCase
{
    public function testConnection()
    {
        $connection = Database::getConnection();
        self::assertNotNull($connection);
    }

    public function testSingletonDatabase()
    {
        $con1 = Database::getConnection();
        $con2 = Database::getConnection();
        self::assertSame($con1, $con2);
    }
}
