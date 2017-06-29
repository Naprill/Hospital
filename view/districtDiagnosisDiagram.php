<?php
/**
 * Created by PhpStorm.
 * User: ніна
 * Date: 22.04.2017
 * Time: 16:21
 */
spl_autoload_register(function ($class_name) {
    include "../model/". $class_name . '.php';
});
$database = new Database();
$addresses = $database->getRows("SELECT * FROM Address");
$diagnoses = $database->getRows("SELECT * FROM Diagnoses");
$ticks_temp = $database->getRows("Select address_name as address from Address");
$ticks = makeVector($ticks_temp,"address_name",sizeof($ticks_temp));

function makeVector($array, $address, $size){

    $result = array($size);
    for($i=0; $i < $size; $i++) $result[$i] = 0;

    foreach ($array as $elem){
        $result[$elem["$address"]] = $elem["data"];
    }
    return $result;
}

$healthyData_temp =  $database->getRows("
	SELECT
	 Address.address_id as address,
	 COUNT( Patients.patient_id ) as data
    FROM Orders
      JOIN Patients ON Patients.patient_id = Orders.patient_id
      JOIN Diagnoses ON Diagnoses.diagnosis_id = Orders.diagnosis_id
      JOIN Address ON Address.address_id = Patients.address_id
    WHERE Diagnoses.diagnosis_id = 1
    GROUP BY Address.address_id
	");
$healthyData = makeVector($healthyData_temp,"address", sizeof($ticks));

$disbacteriosisData_temp =  $database->getRows("
	SELECT
	 Address.address_id as address,
	 COUNT( Patients.patient_id ) as data
    FROM Orders
      JOIN Patients ON Patients.patient_id = Orders.patient_id
      JOIN Diagnoses ON Diagnoses.diagnosis_id = Orders.diagnosis_id
      JOIN Address ON Address.address_id = Patients.address_id
    WHERE Diagnoses.diagnosis_id = 2
    GROUP BY Address.address_id
	");
$disbacteriosisData = makeVector($disbacteriosisData_temp,"address", sizeof($ticks));

$ulcerData_temp =  $database->getRows("
	SELECT
	 Address.address_id as address,
	 COUNT( Patients.patient_id ) as data
    FROM Orders
      JOIN Patients ON Patients.patient_id = Orders.patient_id
      JOIN Diagnoses ON Diagnoses.diagnosis_id = Orders.diagnosis_id
      JOIN Address ON Address.address_id = Patients.address_id
    WHERE Diagnoses.diagnosis_id = 3
    GROUP BY Address.address_id
	");
$ulcerData = makeVector($ulcerData_temp,"address", sizeof($ticks));

//print_r($ticks);
//print_r($disbacteriosisData_temp);
//echo "<br>";
//print_r($disbacteriosisData);
//echo "<br>";
//echo json_encode(array_values($ticks));

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>

    <link rel="stylesheet/less" type="text/css" href="../css/style.less" />
    <script src="../js/less.min.js"></script>

    <!--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>-->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <script type="text/javascript" src="../js/jquery.jqplot.js"></script>
    <script type="text/javascript" src="../js/jqplot/jqplot.barRenderer.js"></script>
    <script type="text/javascript" src="../js/jqplot/jqplot.pieRenderer.js"></script>
    <script type="text/javascript" src="../js/jqplot/jqplot.categoryAxisRenderer.js"></script>
    <script type="text/javascript" src="../js/jqplot/jqplot.pointLabels.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/jquery.jqplot.css" />


    <title>Чернівецька обласна лікарня</title>
</head>
<body >

<?php include "header.php"; ?>

<table class="searchTable">
    <caption>
        Райони
    </caption>
    <thead>
    <tr>
        <th class="subheader" scope="col" >номер</th>
        <th class="subheader" scope="col" >район</th>
    </tr>
    </thead>

    <tbody>
    <?php  foreach ($addresses as $elem) : ?>
        <tr>
            <td scope="row"> <?php echo $elem['address_id']; ?> </td>
            <td scope="row"> <?php echo $elem['address_name']; ?> </td
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<table class="searchTable">
    <caption>
        Діагнози
    </caption>
    <thead>
    <tr>
        <th class="subheader" scope="col" >номер</th>
        <th class="subheader" scope="col" >район</th>
    </tr>
    </thead>

    <tbody>
    <?php  foreach ($diagnoses as $elem) : ?>
        <tr>
            <td scope="row"> <?php echo $elem['diagnosis_id']; ?> </td>
            <td scope="row"> <?php echo $elem['diagnosis_name']; ?> </td
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<h2>Діаграма розподілу діагнозів по районах</h2>


<div><span>Moused Over: </span><span id="info">Nothing</span></div>

<div id="chart" style="margin-top:20px; margin-left:20px; width:1200px; height:300px;"></div>
<pre class="code brush:js"></pre>

<?php include "footer.html"; ?>

<script class="code" type="text/javascript">$(document).ready(function(){
        var s1 = <?php echo json_encode($healthyData);?>;
        var s2 = <?php echo json_encode($disbacteriosisData);?>;
        var s3 = <?php echo json_encode($ulcerData);?>;
        var ticks = <?php echo json_encode($ticks);?> ;

//потрібно зробити відмітки у вигляді цифр, а окремо зобразити таблицю з назвами районів.
// Тоді можна буде вивести більше хвороб - для цього треба записувати дані у згенеровані змінні,
// кількість яких залежить від кількості діагнозів у БД

        plot = $.jqplot('chart', [s1, s2, s3], {
            seriesDefaults: {
                renderer:$.jqplot.BarRenderer,
                pointLabels: { show: true }
            },
            axes: {
                xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer,
                    ticks: ticks
                }
            }
        });

        $('#chart').bind('jqplotDataHighlight',
            function (ev, seriesIndex, pointIndex) {
                $('#info').html('diagnosis: '+(seriesIndex+1)+', address: '+(pointIndex+1));
            }
        );

        $('#chart').bind('jqplotDataUnhighlight',
            function (ev) {
                $('#info').html('Nothing');
            }
        );
    });</script>
</body>
</html>

