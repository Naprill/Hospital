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

    $address_id = $_POST['address'];
    $parameter_id = $_POST['parameter'];
    $params = array();
    $one_was = false;

    if (strlen($address_id) != 0 || strlen($parameter_id) != 0){
        $query = $query." WHERE ";
    }

    if (strlen($address_id) != 0){
        $query = $query." Address.address_id = ? ";
        $one_was = true;
        array_push($params, $address_id);
    }

    if (strlen($parameter_id)){
        if ($one_was) {
            $query = $query." AND";
        }

        $query = $query."
            Results.result_id IN(
                SELECT 
                    Results.result_id 
                FROM 
                    Results 
                    JOIN Parameters ON Results.parameter_id = Parameters.parameter_id 
                WHERE 
                    Parameters.parameter_id = ?
                    AND(
                        Results.result <= Parameters.norm_min 
                        OR Results.result >= Parameters.norm_max
                    )
            ) ";
        $one_was = true;
        array_push($params, $parameter_id);
    }
    $searchResult = $database->getRows($query, $params);
}


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

<h2>Знайти аналізи за адресою і параметром</h2>

<form action="searchAnalysis.php" method="post">
    <div class="info">
        <label>Місце проживання:
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
                    <option value='<?php echo $parameter['parameter_id']; ?>'><?php echo $parameter['parameter_name']; ?></option>;
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
        <th class="subheader" scope="col" >№</th>
        <th class="subheader" scope="col" >Перегляд</th>
        <th class="subheader" scope="col" >№ зам.</th>
        <th class="subheader" scope="col" >Ім'я</th>
        <th class="subheader" scope="col" >Дата завершення аналізу</th>
        <th class="subheader" scope="col" >Назва аналізу</th>
    </tr>
    </thead>

    <tbody>

    <?php  $i=0; foreach ($searchResult as $result) : ?>
        <tr>
            <td scope="row"> <?php echo $i; $i++; ?> </td>
            <td><a class="button_view" target="_blank" href="viewAnalysis.php?order_id=<?php echo $result['order_id']; ?>"> </a></td>
            <td> <?php echo $result['order_id']?> </td>
            <td scope="row"> <?php echo $result['patient_name'] ?> </td>
            <td>  <?php echo $result['completion_date'] ?> </td>
            <td> <?php echo $result['analysis_name']?> </td>
        </tr>
    <?php endforeach; ?>

    </tbody>
</table>
<img class="mockup" src="../css/footer.jpg">
</body>
</html>
