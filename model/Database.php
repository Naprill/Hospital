<?php

/**
 * Created by PhpStorm.
 * User: ніна
 * Date: 16.02.2017
 * Time: 18:29
 */
class Database
{
    public $isConnected;
    public $connection;

    public function __construct()
    {
        $this->connection = Connector::instance();
        $this->isConnected = true;
    }

    public function getRow($query, $params = array())
    {
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute($params);
            return $statement->fetch(PDO::FETCH_LAZY);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getRows($query, $params = array())
    {
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute($params);
            return $statement->fetchAll();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getColumn($query, $param){
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute(array($param));
            return $statement->fetchColumn();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function insertOrDeleteRow($query, $params)
    {
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute($params);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function getLastInsertId(){
        return $this->connection->lastInsertId();
    }
}