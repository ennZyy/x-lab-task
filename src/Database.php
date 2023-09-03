<?php

namespace App;

use InvalidArgumentException;
use PDO;
use PDOException;

class Database
{
    private PDO $connection;

    public function __construct()
    {
        try {
            $this->connection = new PDO(DSN, DB_USER, DB_PASSWORD);
        } catch (PDOException $e) {
            throw new InvalidArgumentException("Something went wrong! Database error: " . $e->getMessage());
        }
    }

    public function getConnection():PDO
    {
        return $this->connection;
    }
}