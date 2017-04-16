<?php

/**
 * Created by PhpStorm.
 * User: ніна
 * Date: 16.04.2017
 * Time: 8:03
 */
class Delete
{

    public $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    public function deleteDiagnosis($diagnosis_id){
        $this->database->insertOrDeleteRow("Delete from Diagnoses WHERE diagnosis_id=?",[
            $diagnosis_id
        ]);
    }
}