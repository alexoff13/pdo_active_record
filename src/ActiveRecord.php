<?php

namespace Worker;

use PDO;

require_once('../vendor/autoload.php');
$dotenv = \Dotenv\Dotenv::createImmutable('../');

$dotenv->load();


class ActiveRecord
{
    protected static $connection;

    protected static function connect()
    {
        if (!isset(self::$connection)) {
            self::$connection = new PDO("mysql:host=" . $_ENV['MYSQL_HOST'] . ";port=" . $_ENV['MYSQL_PORT'] . ";",
                $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD']);
        }
    }

    protected static function unsetConnect()
    {
        self::$connection = null;
    }
}