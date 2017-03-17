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
        $this->database->insertRow("INSERT INTO Analyzes(analysis_name) VALUES (?)",[
            $name
        ]);
    }


    public function insertParameter($name, $unit, $normMin, $normMax, $analysisId){
        $this->database->insertRow("INSERT INTO Parameters(parameter_name, unit, norm_min, norm_max, analysis_id) VALUES (?,?,?,?,?)",[
            $name,
            $unit,
            $normMin,
            $normMax,
            $analysisId
        ]);
    }

    public function insertPatient($name, $age, $sex, $address){
        //1 пара у 31 ауд Понеділок
        $this->database->insertRow("INSERT INTO Patients(patient_name, birthdate, sex, address_id) VALUES (?, ?, ?, ?)", [
                $name,
                $age,
                $sex,
                $address
            ]);
        $newPatientId = $this->database->getLastInsertId();

        return $newPatientId;
    }

    public  function insertOrder($patientId, $diagnosis, $analysis, $coverDiagnosis, $receivingDate, $completionDate, $laboratory){
        //echo $orderId;
        $this->database->insertRow("INSERT INTO Orders(patient_id, diagnosis_id, analysis_id, cover_diagnosis, receiving_date, completion_date, laboratory) VALUES (?, ?, ?, ?, ?, ?, ?)",[
            $patientId,
            $diagnosis,
            $analysis,
            $coverDiagnosis,
            $receivingDate,
            $completionDate,
            $laboratory
        ]);
        $newOrderId = $this->database->getLastInsertId();

        return $newOrderId;
    }


    public function insertResult($parameterId, $orderId, $result){
        $this->database->insertRow("INSERT INTO Results(parameter_id, order_id, result) VALUES (?, ?, ?)",[
            $parameterId,
            $orderId,
            $result
        ]);
    }
}