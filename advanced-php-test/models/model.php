<?php

class Model {
    protected $database = "nba2019";
    protected $connection;
    public function __construct() {
        // define connection
        $this->connection = new mysqli('localhost', 'root', '', $database);
    }

    public function getConnection() {
        return $this->connection;
    }
}
