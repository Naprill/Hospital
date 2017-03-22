<?php

spl_autoload_register(function ($class_name) {
    include "../model/". $class_name . '.php';
});
$database = new Database();
$addresses = $database->getRows("SELECT * FROM Address");
$parameters = $database->getRows("SELECT * FROM Parameters");
$query = "SELECT DISTINCT
                Patients.patient_name,
                Orders.completion_date, 
                Orders.order_id, 
                Analyzes.analysis_name 
            FROM 
                Orders 
                JOIN Patients ON Patients.patient_id = Orders.patient_id 
                JOIN Results ON Results.order_id = Orders.order_id 
                JOIN Address ON Address.address_id = Patients.address_id 
                JOIN Diagnoses ON Diagnoses.diagnosis_id = Orders.diagnosis_id 
                JOIN Analyzes ON Analyzes.analysis_id = Orders.analysis_id";

$searchResult = $database->getRows($query);

if (isset($_POST['send'])) {

    if (isset($_POST['address'])){
        $query = $query." WHERE 
                Address.address_id = ? ";
        $searchResult = $database->getRows($query,[$_POST['address']]);
    }

    if (isset($_POST['parameter'])){
        $query = $query." WHERE 
                .address_id = ?; ";
        $searchResult = $database->getRows($query,[$_POST['address']]);
    }

}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="../css/style.css">

    <title>Чернівецька обласна лікарня</title>
</head>
<body >
<header>
    <?php include "header.php"; ?>
</header>

<h2>Знайти аналіз</h2>

<form action="searchAnalysis.php" method="post">
    <div class="info">
        <label>Адреса:
            <select name="address">
                <?php foreach ($addresses as $address) : ?>
                    <option value='<?php echo $address['address_id']; ?>'><?php echo $address['address_name']; ?></option>;
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    <div class="info">
        <label>Параметр не в нормі:
            <select name="parameter">
                <?php foreach ($parameters as $parameter) : ?>
                    <option value='<?php echo $parameter['']; ?>'><?php echo $address['']; ?></option>;
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    <input type="submit" name="send" value="Знайти" required/>
</form>


<table class="searchTable">
    <caption>Результат пошуку
    </caption>
    <thead>
    <tr>
        <th class="subheader" scope="col" >Перегляд</th>
        <th class="subheader" scope="col" >№ зам.</th>
        <th class="subheader" scope="col" >Ім'я</th>
        <th class="subheader" scope="col" >Дата завершення аналізу</th>
        <th class="subheader" scope="col" >Назва аналізу</th>
    </tr>
    </thead>

    <tbody>

    <?php foreach ($searchResult as $result) : ?>
        <tr>
            <td><a class="button_view" target="_blank" href="viewAnalysis.php?order_id=<?php echo $result['order_id']; ?>"> </a></td>
            <td> <?php echo $result['order_id']?> </td>
            <td scope="row"> <?php echo $result['patient_name'] ?> </td>
            <td>  <?php echo $result['completion_date'] ?> </td>
            <td> <?php echo $result['analysis_name']?> </td>
        </tr>
    <?php endforeach; ?>

    </tbody>
</table>

</body>
</html>
