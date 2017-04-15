<?php

spl_autoload_register(function ($class_name) {
    include "../model/". $class_name . '.php';
});

$order_id = $_GET['order_id'];
$database = new Database();
$patientData = $database->getRow("SELECT 
	Patients.patient_name, 
	Patients.birthdate, 
	Patients.sex,
	Address.address_name, 
	Orders.completion_date,
	Orders.cover_diagnosis,
	Orders.laboratory, 
	Orders.treatment,
	Diagnoses.diagnosis_name 
FROM 
	Patients 
	JOIN Address ON Address.address_id = Patients.address_id 
	JOIN Orders ON Orders.patient_id = Patients.patient_id 
	JOIN Diagnoses on Diagnoses.diagnosis_id = Orders.diagnosis_id 
WHERE 
	Orders.order_id = ".$order_id);

$parametersData =  $database->getRows("SELECT 
	Results.result, 
	Parameters.parameter_name, 
	Parameters.unit, 
	Parameters.norm_min, 
	Parameters.norm_max,
	Analyzes.analysis_name 
FROM 
	Parameters 
	JOIN Results ON Results.parameter_id = Parameters.parameter_id 
	JOIN Analyzes On Analyzes.analysis_id = Parameters.analysis_id
    	JOIN Orders on Orders.order_id = Results.order_id
WHERE
	Orders.order_id = ".$order_id);


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>

    <link rel="stylesheet/less" type="text/css" href="../css/style.less" />
    <script src="../js/less.min.js"></script>

    <title>Чернівецька обласна лікарня</title>
</head>
<body >

<?php include "header.php"; ?>

<h2>Перегляд аналізу</h2>

    <div class="info">
        <label>Пацієнт: <?php echo $patientData['patient_name'] ?> </label>
    </div>
    <div class="info">
        <label>Дата народження: <?php echo $patientData['birthdate'] ?> </label>
    </div>
    <div class="info">
        <label>Стать:
            <?php
            if($patientData['sex']=="Female")
                echo "Жіноча";
            else echo "Чоловіча"
            ?>
        </label>
    </div>
    <div class="info">
        <label>Місце проживання: <?php echo $patientData['address_name'] ?> </label>
    </div>


    <table class="viewTable">
        <caption> <?php echo $parametersData[1]['analysis_name'] ?>
        </caption>
        <thead>
        <tr>
            <th class="subheader" scope="col" >Показник</th>
            <th class="subheader" scope="col" >Референтний інтервал</th>
            <th class="subheader" scope="col" >Результат</th>
            <th class="subheader" scope="col" >Одиниці</th>
        </tr>
        </thead>

        <tbody>

        <?php foreach ($parametersData as $parameter) :
            $norm_hit = true;   ?>
            <tr>
                <th scope="row"> <?php echo $parameter['parameter_name'] ?> </th>
                <td>
                    <?php
                    if($parameter['norm_min']==0 && $parameter['norm_max']==0){
                        echo "відсутній";
                        if($parameter['result']!=0)$norm_hit = false;
                    }
                    else if($parameter['norm_max']==1000){
                        echo " > ".$parameter['norm_min'];
                        if($parameter['result'] <= $parameter['norm_min'])$norm_hit = false;
                    }
                    else if($parameter['norm_min']==0){
                        echo " < ".$parameter['norm_max'];
                        if($parameter['result'] >= $parameter['norm_max'])$norm_hit = false;
                    }
                    else{
                        echo "від ".$parameter['norm_min']." до ".$parameter['norm_max'];
                        if($parameter['result'] <= $parameter['norm_min'] || $parameter['result'] >= $parameter['norm_max'] )
                            $norm_hit = false;
                    }
                    ?>
                </td>
                <td <?php if(!$norm_hit)echo 'class="attention"'?> >  <?php echo $parameter['result'] ?> </td>
                <td> <?php echo $parameter['unit']?> </td>
            </tr>
        <?php endforeach; ?>


        </tbody>
    </table>

    <div class="info">
        <label>Заключення: <?php echo $patientData['diagnosis_name'] ?>  </label>
    </div>
    <div class="info">
        <label>Супутній діагноз: <?php echo $patientData['cover_diagnosis'] ?> </label>
    </div>
    <div class="info">
        <label>Дата закінчення аналізу: <?php echo $patientData['completion_date'] ?> </label>
    </div>
    <div class="info">
        <label>Місце здачі аналізу: <?php echo $patientData['laboratory'] ?> </label>
    </div>
    <div class="info">
        <label>Проведене лікування: <?php echo $patientData['treatment'] ?></label>
    </div>
<img class="mockup" src="../css/footer.jpg">
</body>
</html>
