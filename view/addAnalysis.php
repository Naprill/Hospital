<?php
spl_autoload_register(function ($class_name) {
    include "../model/". $class_name . '.php';
});

if (isset($_POST['send1'])){
    $insertObj = new Insert();
    $newAnalysisId = $insertObj->insertAnalysis($_POST['analysis']);
    header("Location: addAnalysis.php?analysis_id=".$newAnalysisId); exit;
}
$database = new Database();
$analyzes = $database->getRows("SELECT * FROM Analyzes"); ///!!!!!!!

if (isset($_POST['send2'])){
    $insertObj = new Insert();
    $insertObj->insertParameter(
        $_POST['parameter'],
        $_POST['unit'],
        $_POST['norm_min'],
        $_POST['norm_max'],
        $_POST['analysis']
    );

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

<?php include "header.php"; ?>

<h2>Додавання аналізу</h2>

<form action="addAnalysis.php" method="post">

    <div class="info">
        <label>Назва аналізу:
            <input name="analysis" title="analysis" type="text">
        </label>
    </div>

    <input type="submit" name="send1" value="Створити" required/>
</form>
<hr>
<h2>Додавання параметрів</h2>
<form action="addAnalysis.php" method="post">

    <div class="info">
        <label>Пакет аналізів:
            <select name="analysis">
                <?php foreach ($analyzes as $analysis) : ?>
                    <option <?php if($analysis['analysis_id'] == $_GET[analysis_id]) echo "selected "?>value='<?php echo $analysis['analysis_id']; ?>'><?php echo $analysis['analysis_name']; ?></option>;
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    <div class="info">
        <label>Назва параметра:
            <input name="parameter" title="parameter" type="text">
        </label>
    </div>
    <div class="info">
        <label>Одиниці вимірювання:
            <input name="unit" title="unit" type="text">
        </label>
    </div>

    <div class="info">
        <label>Нижня межа норми :
            <input name="norm_min" title="norm_min" type="text">
        </label>
    </div>
    <div class="info">
        <label>Верхня межа норми:
            <input name="norm_max" title="norm_max" type="text">
        </label>
    </div>
    <input type="submit" name="send2" value="Додати" required/>
</form>
<div class="tips">
    <table border>
        <caption>Підказка по створенню параметра
        </caption>
        <thead>
        <tr>
            <th class="subheader" scope="col" >Нижня межа</th>
            <th class="subheader" scope="col" >Верхня межа</th>
            <th class="subheader" scope="col" >Результат</th>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td> <span>number</span> </td>
            <td> 1000 </td>
            <td> > <span>number</span> </td>
        </tr>
        <tr>
            <td> 0 </td>
            <td> <span>number</span> </td>
            <td> < <span>number</span> </td>
        </tr>
        <tr>
            <td> <span>number1</span> </td>
            <td> <span>number2</span> </td>
            <td> від <span>number1</span> до <span>number2</span> </td>
        </tr>
        <tr>
            <td> 0 </td>
            <td> 0 </td>
            <td> відсутній </td>
        </tr>
        </tbody>
    </table>
</div>
<img class="mockup" src="../css/footer.jpg">
</body>
</html>
