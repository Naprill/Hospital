<?php

spl_autoload_register(function ($class_name) {
    include "../model/". $class_name . '.php';
});

$database = new Database();
$addresses = $database->getRows("SELECT * FROM Address");
$parameters = $database->getRows("SELECT * FROM Parameters");
$diagnoses = $database->getRows("SELECT * FROM Diagnoses");
$analyses = $database->getRows("SELECT * FROM Analyzes");

$query = "SELECT DISTINCT
                Patients.patient_name
            FROM 
                Orders 
                JOIN Patients ON Patients.patient_id = Orders.patient_id 
                JOIN Results ON Results.order_id = Orders.order_id 
                JOIN Address ON Address.address_id = Patients.address_id 
                JOIN Diagnoses ON Diagnoses.diagnosis_id = Orders.diagnosis_id 
                JOIN Analyzes ON Analyzes.analysis_id = Orders.analysis_id";

function getArray($query, $id){
    $database = new Database();
    $searchResult = $database->getRows($query, [$id]);
    return $searchResult;
}
$districtQuery = $query." Where Patients.address_id = ?;";
$districtReport = array();
$i = 0;
foreach ($addresses as $address){
    $searchResult = getArray($districtQuery,$address['address_id']);
    $districtReport[$i]['district'] = $address['address_name'];
    $districtReport[$i]['count'] = count($searchResult);
    $i++;
}

$diagnosesQuery = $query." Where Orders.diagnosis_id = ?;";
$diagnosesReport = array();
$i = 0;

foreach ($diagnoses as $diagnosis){
    $searchResult = getArray($diagnosesQuery,$diagnosis['diagnosis_id']);
    $diagnosesReport[$i]['diagnosis'] = $diagnosis['diagnosis_name'];
    $diagnosesReport[$i]['count'] = count($searchResult);
    $i++;
}

$ageQuery = $query." Where Orders.diagnosis_id = ?;";
$ageReport = array();
$ageReport[0]["age"] = "Молодий: 18-44";
$ageReport[1]["age"] = "Середній: 45-59";
$ageReport[2]["age"] = "Літній: 60-74";
$ageReport[3]["age"] = "Старечий: 75-90";

$query1 = $query." Where DATEDIFF( CURDATE( ) , Patients.birthdate ) <= 16071
                         AND DATEDIFF( CURDATE( ) , Patients.birthdate ) >= 6575 ";
$ageReport[0]["count"] = count($database->getRows($query1));


$query2 = $query." Where DATEDIFF( CURDATE( ) , Patients.birthdate ) <= 21550
                         AND DATEDIFF( CURDATE( ) , Patients.birthdate ) > 16071 ";
$ageReport[1]["count"] = count($database->getRows($query2));


$query3 = $query." Where DATEDIFF( CURDATE( ) , Patients.birthdate ) <= 21550
                         AND DATEDIFF( CURDATE( ) , Patients.birthdate ) > 16071 ";
$ageReport[2]["count"] = count($database->getRows($query3));

$query4 = $query." Where DATEDIFF( CURDATE( ) , Patients.birthdate ) <= 32873
                         AND DATEDIFF( CURDATE( ) , Patients.birthdate ) > 27029 ";
$ageReport[3]["count"] = count($database->getRows($query4));


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
<header>
    <?php include "header.php"; ?>
</header>

<h2>Загальний Звіт</h2>

<table class="searchTable">
    <caption>
        Кількість пацієнтів по району
    </caption>
    <thead>
    <tr>
        <th class="subheader" scope="col" >Район</th>
        <th class="subheader" scope="col" >Кількість пацієнтів</th>
    </tr>
    </thead>

    <tbody>
    <?php  foreach ($districtReport as $elem) : ?>
        <tr>
            <td scope="row"> <?php echo $elem['district']; ?> </td>
            <td scope="row"> <?php echo $elem['count']; ?> </td
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<table class="searchTable">
    <caption>
        Кількість пацієнтів з певним діагнозом
    </caption>
    <thead>
    <tr>
        <th class="subheader" scope="col" >Діагноз</th>
        <th class="subheader" scope="col" >Кількість пацієнтів</th>
    </tr>
    </thead>

    <tbody>
    <?php  foreach ($diagnosesReport as $elem) : ?>
        <tr>
            <td scope="row"> <?php echo $elem['diagnosis']; ?> </td>
            <td scope="row"> <?php echo $elem['count']; ?> </td
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<table class="searchTable">
    <caption>
        Кількість пацієнтів у віковій групі
    </caption>
    <thead>
    <tr>
        <th class="subheader" scope="col" >Вікова група</th>
        <th class="subheader" scope="col" >Кількість пацієнтів</th>
    </tr>
    </thead>

    <tbody>
    <?php  foreach ($ageReport as $elem) : ?>
        <tr>
            <td scope="row"> <?php echo $elem["age"];  ?> </td>
            <td scope="row"> <?php echo $elem['count']; ?> </td
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php include "footer.html"; ?>
</body>
</html>
