<?php

namespace app\models;

use app\classes\Bind;
use PDO;
use PDOException;

class Connection {

    private static $pdo;

    public static function connect() {
        if (!isset(self::$pdo)) {
            try {
                $config = (object) Bind::get('config')->database;
                
                self::$pdo = new PDO("mysql:host=$config->host;dbname=$config->dbname;charset=$config->charset", $config->username, $config->password, $config->options);
            } catch (PDOException $e) {
                die("Erro na conexão: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }

}