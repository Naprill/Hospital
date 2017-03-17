<?php

/**
 * Created by PhpStorm.
 * User: ніна
 * Date: 10.03.2017
 * Time: 20:30
 */
class Insert
{
    public $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    public function insertAnalysis($name){
        $newAnalysisId = $this->database->getLastIndexInTable("Analyzes");
        $this->database->insertRow("INSERT INTO Analyzes VALUES (?,?)",[
            $newAnalysisId,
            $name
        ]);
    }


    public function insertParameter($name, $unit, $normMin, $normMax, $analysisId){
        $newParameterId = $this->database->getLastIndexInTable("Parameter");
        $this->database->insertRow("INSERT INTO Parameters VALUES (?,?,?,?,?,?)",[
            $newParameterId,
            $name,
            $unit,
            $normMin,
            $normMax,
            $analysisId
        ]);
    }

    public function insertPatient($name, $age, $sex, $address){
        $newPatientId = $this->database->getLastIndexInTable("Patient");
        //LastInsertedId
        //1 пара у 31 ауд Понеділок
        $this->database->insertRow("INSERT INTO Patients VALUES (?, ?, ?, ?, ?)", [
                $newPatientId,
                $name,
                $age,
                $sex,
                $address
            ]);
        return $newPatientId;
    }

    public  function insertOrder($patientId, $diagnosis, $analysis, $coverDiagnosis, $receivingDate, $completionDate, $laboratory){
        $newOrderId = $this->database->getLastIndexInTable("Orders");
        //echo $orderId;
        $this->database->insertRow("INSERT INTO Orders VALUES (?, ?, ?, ?, ?, ?, ?, ?)",[
            $newOrderId,
            $patientId,
            $diagnosis,
            $analysis,
            $coverDiagnosis,
            $receivingDate,
            $completionDate,
            $laboratory
        ]);
        return $newOrderId;
    }


    public function insertResult($parameterId, $orderId, $result){
        $newResultId = $this->database->getLastIndexInTable("Results");
        $this->database->insertRow("INSERT INTO Results VALUES (?, ?, ?, ?)",[
            $newResultId,
            $parameterId,
            $orderId,
            $result
        ]);
    }
}