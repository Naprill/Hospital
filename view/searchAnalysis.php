<?php

spl_autoload_register(function ($class_name) {
    include "../model/". $class_name . '.php';
});

$database = new Database();
$addresses = $database->getRows("SELECT * FROM Address");
$parameters = $database->getRows("SELECT * FROM Parameters");
$diagnoses = $database->getRows("SELECT * FROM Diagnoses");

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

function searchAnalysis($query){
    $database = new Database();
    $params = array();
    $one_was = false;

    $sex = $_POST['sex'];
    $address_id = $_POST['address'];
    $parameter_id = $_POST['parameter'];
    $diagnosis_id = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];

    if (strlen($sex) || strlen($address_id) || strlen($parameter_id) || strlen($diagnosis_id) || strlen($treatment)){
        $query = $query." WHERE ";
    }

    if (strlen($sex)){
        $sex = "$sex";
        $query = $query." Patients.sex LIKE ? ";
        $one_was = true;
        array_push($params, $sex);
    }

    if (strlen($address_id)){
        if ($one_was) {
            $query = $query." AND";
        }
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

    if(strlen($diagnosis_id)){
        if ($one_was) {
            $query = $query." AND";
        }
        $query = $query." Orders.diagnosis_id = ? ";
        $one_was = true;
        array_push($params, $diagnosis_id);
    }

    if (strlen($treatment)){
        //parse_str($treatment);
        $treatment = "%$treatment%";
        $query = $query." Orders.treatment LIKE ? ";
        //$query = $query."OR Orders.treatment LIKE ?";
        $one_was = true;
        array_push($params, $treatment);
    }

    $searchResult = $database->getRows($query, $params);
    return $searchResult;
}

if(isset($_POST['send'])){
    $searchResult = searchAnalysis($query);
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

<h2>Знайти аналізи за критеріями</h2>

<form action="searchAnalysis.php" method="post">
    <div class="allFields">
        <div class="field">
        <label for="1">Стать:</label>
            <select id="1" name="sex">
                <option value="">---</option>
                <option <?php if($_POST['sex']=="Female") echo "selected "?> value='Female'>Жіноча</option>
                <option <?php if($_POST['sex']=="Male") echo "selected "?> value='Male'>Чоловіча</option>
            </select>
        </div>
        <div class="field">
            <label for="2">Місце проживання:</label>
            <select id="2" name="address">
                    <option value="">---</option>
                <?php foreach ($addresses as $address) : ?>
                    <option <?php if($address['address_id'] == $_POST['address']) echo "selected "?> value='<?php echo $address['address_id']; ?>'><?php echo $address['address_name']; ?></option>;
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="3">Параметр не в нормі:</label>
            <select id="3" name="parameter">
                    <option value="">---</option>
                <?php foreach ($parameters as $parameter) : ?>
                    <option <?php if($parameter['parameter_id'] == $_POST['parameter']) echo "selected "?> value='<?php echo $parameter['parameter_id']; ?>'><?php echo $parameter['parameter_name']; ?></option>;
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="4">Заключення:</label>
            <select id="4" name="diagnosis">
                <option value="">---</option>
                <?php foreach ($diagnoses as $diagnosis) : ?>
                    <option <?php if($diagnosis['diagnosis_id'] == $_POST['diagnosis']) echo "selected "?> value='<?php echo $diagnosis['diagnosis_id']; ?>'><?php echo $diagnosis['diagnosis_name']; ?></option>;
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="5">Проведене лікування: </label>
            <input id="5" class="input70" type="text" name="treatment" value='<?php if(strlen($_POST['treatment'])) echo $_POST['treatment'];?>'>
        </div>
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
