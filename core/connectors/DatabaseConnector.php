<?php

namespace connectors;
require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use PDO;

class DatabaseConnector
{
    public static function getPDO(): PDO
    {
        $dotenv = Dotenv::createMutable(__DIR__ . "/../../");
        $dotenv->load();
        $pdo = new PDO("mysql:host={$_ENV["MYSQL_HOST"]};dbname={$_ENV["MYSQL_DATABASE"]}", $_ENV["MYSQL_USER"], $_ENV["MYSQL_PASSWORD"]);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}
