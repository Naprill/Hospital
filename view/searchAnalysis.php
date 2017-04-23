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

    $age_type = $_POST['age'];
    $sex = $_POST['sex'];
    $address_id = $_POST['address'];
    $analysis_id = $_POST['analysis'];
    $parameter_id = $_POST['parameter'];
    $diagnosis_id = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];

    if (strlen($age_type) || strlen($sex) || strlen($address_id) || strlen($analysis_id) || strlen($parameter_id) || strlen($diagnosis_id) || strlen($treatment)){
        $query = $query." WHERE ";
    }

    if (strlen($age_type)){
        if ($one_was) {
            $query = $query." AND";
        }

        if($age_type == 18){
            $query = $query." DATEDIFF( CURDATE( ) , Patients.birthdate ) <= 16071
                         AND DATEDIFF( CURDATE( ) , Patients.birthdate ) >= 6575 ";
        }
        if($age_type == 45){
            $query = $query." DATEDIFF( CURDATE( ) , Patients.birthdate ) <= 21550
                         AND DATEDIFF( CURDATE( ) , Patients.birthdate ) > 16071 ";
        }
        if($age_type == 60){
            $query = $query." DATEDIFF( CURDATE( ) , Patients.birthdate ) <= 27029
                         AND DATEDIFF( CURDATE( ) , Patients.birthdate ) > 21550 ";
        }
        if($age_type == 75){
            $query = $query." DATEDIFF( CURDATE( ) , Patients.birthdate ) <= 32873
                         AND DATEDIFF( CURDATE( ) , Patients.birthdate ) > 27029 ";
        }
        $one_was = true;
    }

    if (strlen($sex)){
        if ($one_was) {
            $query = $query." AND";
        }
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

    if (strlen($analysis_id)){
        if ($one_was) {
            $query = $query." AND";
        }
        $query = $query." Analyzes.analysis_id = ? ";
        $one_was = true;
        array_push($params, $analysis_id);
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
                        Results.result < Parameters.norm_min 
                        OR Results.result > Parameters.norm_max
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
        if ($one_was) {
            $query = $query." AND";
        }
        //parse $treatment
        $arr = explode(" ", $treatment);
        if(sizeof($arr)){
            $arr[0] = "%$arr[0]%";
            $query = $query." (Orders.treatment LIKE ? ";
            array_push($params, $arr[0]);

            for($i=1;$i<sizeof($arr);$i++){
                $arr[$i] = "%$arr[$i]%";
                $query = $query."OR Orders.treatment LIKE ? ";
                array_push($params, $arr[$i]);
            }
            $query = $query.") ";
        }
         $one_was = true;


    }
    //echo $query;
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
            <label for="age">Вік:</label>
            <select id="age" name="age">
                <option value="">---</option>
                <option <?php if($_POST['age']=="18") echo "selected "?> value='18'>Молодий: 18-44</option>
                <option <?php if($_POST['age']=="45") echo "selected "?> value='45'>Середній: 45-59</option>
                <option <?php if($_POST['age']=="60") echo "selected "?> value='60'>Літній: 60-74</option>
                <option <?php if($_POST['age']=="75") echo "selected "?> value='75'>Старечий: 75-90</option>
            </select>
        </div>
        <div class="field">
        <label for="sex">Стать:</label>
            <select id="sex" name="sex">
                <option value="">---</option>
                <option <?php if($_POST['sex']=="Female") echo "selected "?> value='Female'>Жіноча</option>
                <option <?php if($_POST['sex']=="Male") echo "selected "?> value='Male'>Чоловіча</option>
            </select>
        </div>
        <div class="field">
            <label for="address">Місце проживання:</label>
            <select id="address" name="address">
                    <option value="">---</option>
                <?php foreach ($addresses as $address) : ?>
                    <option <?php if($address['address_id'] == $_POST['address']) echo "selected "?> value='<?php echo $address['address_id']; ?>'><?php echo $address['address_name']; ?></option>;
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="analysis">Вид аналізу:</label>
            <select id="analysis" name="analysis">
                <option value="">---</option>
                <?php foreach ($analyses as $analysis) : ?>
                    <option <?php if($analysis['analysis_id'] == $_POST['analysis']) echo "selected "?> value='<?php echo $analysis['analysis_id']; ?>'><?php echo $analysis['analysis_name']; ?></option>;
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="parameter">Параметр не в нормі:</label>
            <select id="parameter" name="parameter">
                    <option value="">---</option>
                <?php foreach ($parameters as $parameter) : ?>
                    <option <?php if($parameter['parameter_id'] == $_POST['parameter']) echo "selected "?> value='<?php echo $parameter['parameter_id']; ?>'><?php echo $parameter['parameter_name']; ?></option>;
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="diagnosis">Заключення:</label>
            <select id="diagnosis" name="diagnosis">
                <option value="">---</option>
                <?php foreach ($diagnoses as $diagnosis) : ?>
                    <option <?php if($diagnosis['diagnosis_id'] == $_POST['diagnosis']) echo "selected "?> value='<?php echo $diagnosis['diagnosis_id']; ?>'><?php echo $diagnosis['diagnosis_name']; ?></option>;
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="treatment">Проведене лікування: </label>
            <input id="treatment" class="input70" type="text" name="treatment" value="<?php echo $_POST['treatment'];?>">
        </div>
    </div>
    <input type="submit" name="send" value="Знайти" required/>
</form>

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

    <?php  $i=1; foreach ($searchResult as $result) : ?>
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
