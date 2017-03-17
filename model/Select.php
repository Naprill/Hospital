<?php

/**
 * Created by PhpStorm.
 * User: ніна
 * Date: 11.03.2017
 * Time: 14:04
 */
class Select
{
    public $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    public function selectAll($tableName){
        return $this->database->getRows("SELECT * FROM ".$tableName);
    }

    public function  selectAnalysis($analysisId){
        return $this->database->getColumn("SELECT analysis_name FROM Analyzes WHERE analysis_id=?",$analysisId);
    }

    public function selectParameters($analysisId){
        return $this->database->getRows("SELECT * FROM Parameters WHERE analysis_id=".$analysisId);
    }
}