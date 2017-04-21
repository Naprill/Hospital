<?php
/**
 * Created by PhpStorm.
 * User: ніна
 * Date: 05.04.2017
 * Time: 9:55
 */
spl_autoload_register(function ($class_name) {
    include "../model/". $class_name . '.php';
});
$database = new Database();
$data =  $database->getRows("
	SELECT 
	Diagnoses.diagnosis_name AS label, 
	COUNT(Patients.patient_id) AS data	
FROM 
	Orders 
	JOIN Patients ON Patients.patient_id = Orders.patient_id 
	JOIN Diagnoses ON Diagnoses.diagnosis_id = Orders.diagnosis_id 
GROUP BY 
	Diagnoses.diagnosis_name
	");

$data[0]["color"] = "rgb(237, 178, 10)";
$data[1]["color"] = "rgb(29, 167, 16)";
$data[2]["color"] = "rgb(11, 47, 167)";

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

    <script language="javascript" type="text/javascript" src="../js/jquery.js"></script>
    <script language="javascript" type="text/javascript" src="../js/jquery.flot.js"></script>
    <script language="javascript" type="text/javascript" src="../js/jquery.flot.pie.js"></script>


    <title>Чернівецька обласна лікарня</title>
</head>
<body >

<?php include "header.php"; ?>

<h2>Діаграма розподілу пацієнтів по діагнозу</h2>
<div id="placeholder" class="demo-placeholder" style="padding: 0px; position: relative;  height: 300px">
    <canvas class="flot-base" width="550" height="363" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 550px; height: 363px;">
    </canvas>
    <canvas class="flot-overlay" width="550" height="363" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 550px; height: 363px;">
    </canvas>
    <div class="legend">
        <div style="position: absolute; width: 72px; height: 131px; top: 5px; right: 5px; opacity: 0.85; background-color: rgb(255, 255, 255);">
        </div>
        <table style="position:absolute;top:5px;right:5px;;font-size:smaller;color:#545454">
            <tbody>
            <tr>
                <td class="legendColorBox">
                    <div style="border:1px solid #ffffff;padding:1px">
                        <div style="width:4px;height:0;border:5px solid rgb(237, 178, 10);overflow:hidden"></div>
                    </div>
                </td>
                <td class="legendLabel">Series1</td>
            </tr>
            <tr>
                <td class="legendColorBox">
                    <div style="border:1px solid #ccc;padding:1px">
                        <div style="width:4px;height:0;border:5px solid rgb(11, 47, 167);overflow:hidden"></div>
                    </div>
                </td>
                <td class="legendLabel">Series2</td>
            </tr>
            <tr>
                <td class="legendColorBox">
                    <div style="border:1px solid #ccc;padding:1px">
                        <div style="width:4px;height:0;border:5px solid rgb(203,75,75);overflow:hidden"></div>
                    </div>
                </td>
                <td class="legendLabel">Series3</td>
            </tr>
            <tr>
                <td class="legendColorBox">
                    <div style="border:1px solid #ccc;padding:1px">
                        <div style="width:4px;height:0;border:5px solid rgb(29, 167, 16);overflow:hidden"></div>
                    </div>
                </td>
                <td class="legendLabel">Series4</td>
            </tr>
            <tr>
                <td class="legendColorBox">
                    <div style="border:1px solid #ccc;padding:1px">
                        <div style="width:4px;height:0;border:5px solid rgb(148, 64, 237);overflow:hidden"></div>
                    </div>
                </td>
                <td class="legendLabel">Series5</td>
            </tr>
            <tr>
                <td class="legendColorBox">
                    <div style="border:1px solid #ccc;padding:1px">
                        <div style="width:4px;height:0;border:5px solid rgb(189, 102, 0);overflow:hidden"></div>
                    </div>
                </td>
                <td class="legendLabel">Series6</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<img class="mockup" src="../css/footer.jpg">

<script type="text/javascript">

    var data = [],
        series = Math.floor(Math.random() * 6) + 3;

    for (var i = 0; i < series; i++) {
        data[i] = {
            label: "Series" + (i + 1),
            data: Math.floor(Math.random() * 100) + 1
        }
    }
    data = <?php echo json_encode($data);?> ;
    $.plot('#placeholder', data, {
        series: {
            pie: {
                show: true,
                label: {
                    show: true,
                    radius: 0.8,
                    formatter: labelFormatter,
                    background: {
                        opacity: 0.8,
                        color: '#000'
                    }
                }
            }
        }
    });

    function labelFormatter(label, series) {
        return '<div style="font-size:11px; text-align:center; padding:2px; color:white;">' + label + "<br/>" + Math.round(series.percent) + "%</div>";
    }
</script>
</body>
</html>

