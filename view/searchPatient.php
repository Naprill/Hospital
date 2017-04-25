<?php

spl_autoload_register(function ($class_name) {
    include "../model/". $class_name . '.php';
});
$database = new Database();
$patients = $database->getRows("SELECT patient_id, patient_name FROM Patients");
$query = "SELECT DISTINCT
                Orders.order_id, 
                Patients.patient_name,
                Orders.completion_date,                
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

    if (strlen($_POST['patient_id']) != 0 ){
        $query = $query." WHERE
                Patients.patient_id = ?; ";
        $searchResult = $database->getRows($query,[$_POST['patient_id']]);
    }

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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script src="../js/datalist.js"></script>

</head>
<body >
<header>
    <?php include "header.php"; ?>
</header>

<h2>Знайти аналізи пацієнта</h2>

<form action="searchPatient.php" method="post">
    <div class="field">
        <label>Пацієнт:
            <input id="list" type="text" name="patient_name" list="name">
            <input id="list-hidden" type="hidden" name="patient_id">
            <datalist id="name">
                <?php foreach ($patients as $patient) : ?>
                    <option data-value="<?php echo $patient['patient_id']?>"><?php echo $patient['patient_name']; ?></option>;
                <?php endforeach; ?>
            </datalist>
        </label>
    </div><input type="submit" name="send" value="Знайти" required/>
</form>
<script>



</script>
<p class="tips">Знайдено результатів: <?php echo count($searchResult);?></p>
<table class="searchTable">
    <caption>Результат пошуку
    </caption>
    <thead>
    <tr>
        <th class="subheader" scope="col" >№</th>
        <th class="subheader" scope="col" >Перегляд</th>
        <th class="subheader" scope="col" >№ аналізу</th>
        <th class="subheader" scope="col" >Пацієнт</th>
        <th class="subheader" scope="col" >Дата аналізу</th>
        <th class="subheader" scope="col" >Вид аналізу</th>
    </tr>
    </thead>

    <tbody>

    <?php $i=1; foreach ($searchResult as $result) : ?>
        <tr>
            <td scope="row"> <?php echo $i; $i++; ?> </td>
            <td><a class="button_view" href="viewAnalysis.php?order_id=<?php echo $result['order_id']; ?>"> </a></td>
            <td> <?php echo $result['order_id']?> </td>
            <td scope="row"> <?php echo $result['patient_name'] ?> </td>
            <td>  <?php echo $result['completion_date'] ?> </td>
            <td> <?php echo $result['analysis_name']?> </td>
        </tr>
    <?php endforeach; ?>

    </tbody>
</table>
<?php include "footer.html"; ?>
</body>
</html>
