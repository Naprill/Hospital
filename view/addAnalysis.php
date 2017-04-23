<?php
spl_autoload_register(function ($class_name) {
    include "../model/". $class_name . '.php';
});

if (isset($_POST['send1'])){
    $insertObj = new Insert();
    $newAnalysisId = $insertObj->insertAnalysis($_POST['analysis']);
    header("Location: addAnalysis.php?analysis_id=".$newAnalysisId); exit;
}

$newParameterId = 1;
if (isset($_POST['send2'])){
    $insertObj = new Insert();
    $newParameterId = $insertObj->insertParameter(
        $_POST['parameter'],
        $_POST['unit'],
        $_POST['norm_min'],
        $_POST['norm_max'],
        $_POST['analysis']
    );
}
$deleteObj = new Delete();

if (isset($_POST['send3'])){
    $analysisToDelete = $_POST['analysis'];
    $deleteObj->deleteAnalysisParameters($analysisToDelete);
    $deleteObj->deleteAnalysis($analysisToDelete);
}

if (isset($_POST['send4'])){
    $parameterToDelete = $_POST['parameter'];
    $deleteObj->deleteParameter($parameterToDelete);
}

$database = new Database();
$analyzes = $database->getRows("SELECT * FROM Analyzes");
$parameters = $database->getRows("Select * from Parameters");

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

    <div class="field">
        <label>Назва аналізу:
            <input name="analysis" title="analysis" type="text">
        </label>
    </div>

    <input type="submit" name="send1" value="Створити" required/>
</form>
<hr>
<h2>Додавання параметрів</h2>
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

<form action="addAnalysis.php" method="post">
    <div class="allFields">
        <div class="field">
            <label for="analysis">Пакет аналізів: </label>
            <select id="analysis" name="analysis">
                <?php foreach ($analyzes as $analysis) : ?>
                    <option <?php if($analysis['analysis_id'] == $_GET[analysis_id]) echo "selected "?>value='<?php echo $analysis['analysis_id']; ?>'><?php echo $analysis['analysis_name']; ?></option>;
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field">
            <label for="parameter">Назва параметра:</label>
            <input id="parameter" name="parameter" title="parameter" type="text">
        </div>
        <div class="field">
            <label for="unit">Одиниці вимірювання:</label>
            <input id="unit" name="unit" title="unit" type="text">
        </div>
        <div class="field">
            <label for="norm_min">Нижня межа норми :</label>
            <input id="norm_min" name="norm_min" title="norm_min" type="text">
        </div>
        <div class="field">
            <label for="norm_max">Верхня межа норми:</label>
            <input id="norm_max" name="norm_max" title="norm_max" type="text">
        </div>
    </div>
    <input type="submit" name="send2" value="Додати" required/>
</form>

<hr>
<h2>Видалення аналізу</h2>
<p class="tips">Видалення аналізу призведе до виделення шаблону з усіма його параметрами.
    Видалення не можливе, якщо у базі даних збережено результати цього аналізу.</p>
<form action="addAnalysis.php" method="post">

    <div class="field">
        <label>Назва аналізу:
            <select name="analysis">
                <?php foreach ($analyzes as $analysis) : ?>
                    <option <?php if($analysis['analysis_id'] == $_GET[analysis_id]) echo "selected "?>value='<?php echo $analysis['analysis_id']; ?>'><?php echo $analysis['analysis_name']; ?></option>;
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <input type="submit" name="send3" value="Видалити" required/>
</form>

<hr>
<h2>Видалення параметра</h2>

<form action="addAnalysis.php" method="post">

    <div class="field">
        <label>Назва параметра:
            <select name="parameter">
                <?php foreach ($parameters as $parameter) : ?>
                    <option <?php if($parameter['parameter_id'] == $newParameterId) echo "selected "?>value='<?php echo $parameter['parameter_id']; ?>'><?php echo $parameter['parameter_name']; ?></option>;
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <input type="submit" name="send4" value="Видалити" required/>
</form>
<?php include "footer.html"; ?>
</body>
</html>
