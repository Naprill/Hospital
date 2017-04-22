<?php
spl_autoload_register(function ($class_name) {
    include "model/". $class_name . '.php';
});

$selectObj = new Select();
$analyzes = $selectObj->selectAll("Analyzes");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>

    <link rel="stylesheet/less" type="text/css" href="css/style.less" />
    <script src="js/less.min.js"></script>

    <title>Чернівецька обласна лікарня</title>
</head>
<body>
<a href="#"><img src="css/header.jpg"></a>
<ul>
    <li class="dropdown">
        <a href="javascript:void(0)" class="dropdown_button">Редагування шаблонів</a>
        <div class="dropdown-content">
            <a  href="view/addAnalysis.php">Редагування шаблону додавання аналізу</a>
            <a  href="view/addDiagnosis.php">Редагування діагнозів</a>
        </div>
    </li>
    <li class="dropdown">
        <a href="javascript:void(0)" class="dropdown_button">Зберігання результатів аналізів</a>
        <div class="dropdown-content">
            <?php foreach ($analyzes as $analysis) : ?>
                <a href="view/addAnalysisResult.php?analysis_id=<?php echo $analysis['analysis_id']; ?>"><?php echo $analysis['analysis_name']; ?></a>
            <?php endforeach; ?>
        </div>
    </li>
    <li class="dropdown">
        <a href="javascript:void(0)" class="dropdown_button">Пошук</a>
        <div class="dropdown-content">
            <a  href="view/searchAnalysis.php">Пошук за критеріями</a>
            <a  href="view/searchPatient.php">Пошук аналізів пацієнта</a>
        </div>
    </li>
    <li class="dropdown">
        <a href="javascript:void(0)" class="dropdown_button">Статистичні діаграми</a>
        <div class="dropdown-content">
            <a  href="view/patientsDiagnosisDiagram.php">Діаграма розподілу пацієнтів по діагнозу</a>
            <a  href="view/districtDiagnosisDiagram.php">Порівняльна діаграма розподілу захворюваності по районах</a>
        </div>
    </li>
</ul>
<img class="mockup" src="css/main.jpg">
<img class="mockup" src="css/footer.jpg">
</body>
</html>