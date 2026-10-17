<?php

namespace app\models;

use app\classes\Bind;

abstract class Model {

    protected $connection;

    public function __construct() {
        $this->connection = Bind::get('connection');
    }

    public function all() {
        $sql = "select * from {$this->table}";
        $list = $this->connection->prepare($sql);
        $list->execute();

        return $list->fetchAll();
    }

}