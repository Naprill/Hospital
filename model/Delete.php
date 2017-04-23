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
        $this->database->insertOrDeleteRow("Delete from Diagnoses WHERE diagnosis_id = ?",[
            $diagnosis_id
        ]);
    }

    public function deleteAnalysisParameters($analysis_id){
        $this->database->insertOrDeleteRow("Delete from Parameters WHERE analysis_id = ?",[
            $analysis_id
        ]);
    }

    public function deleteAnalysis($analysis_id){
        $this->database->insertOrDeleteRow("Delete from Analyzes WHERE analysis_id = ?",[
            $analysis_id
        ]);
    }
    public function deleteParameter($parameter_id){
        $this->database->insertOrDeleteRow("Delete from Parameters WHERE parameter_id = ?",[
            $parameter_id
        ]);
    }

}