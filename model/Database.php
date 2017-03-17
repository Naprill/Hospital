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

    public function insertRow($query, $params)
    {
        try {
            $statement = $this->connection->prepare($query);
            $statement->execute($params);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function getLastIndexInTable($tableName){
        $statement = $this->connection->prepare('SHOW TABLE STATUS LIKE \'?\'');
        $statement->execute([$tableName]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return intval($row['Auto_increment']);
    }
}