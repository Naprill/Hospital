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

    <link rel="stylesheet" href="css/style.css">

    <title>Чернівецька обласна лікарня</title>
</head>
<body>
<a href="#"><img src="css/header.jpg"></a>
<ul>
    <li class="dropdown">
        <a href="javascript:void(0)" class="dropdown_button">Змінити шаблони</a>
        <div class="dropdown-content">
            <a  href="view/addAnalysis.php">Створити новий шаблон додавання аналізу</a>
            <!--              <a  href="#">Редагувати шаблон додавання аналізу</a>-->
        </div>
    </li>
    <li class="dropdown">
        <a href="javascript:void(0)" class="dropdown_button">Зберегти результати</a>
        <div class="dropdown-content">
            <?php foreach ($analyzes as $analysis) : ?>
                <a href="view/addAnalysisResult.php?analysis_id=<?php echo $analysis['analysis_id']; ?>"><?php echo $analysis['analysis_name']; ?></a>
            <?php endforeach; ?>
        </div>
    </li>
    <li class="dropdown">
        <a href="javascript:void(0)" class="dropdown_button">Пошук</a>
        <div class="dropdown-content">
            <a  href="view/searchAnalysis.php">Знайти аналізи за адресою та параметром</a>
            <a  href="view/searchPatient.php">Знайти аналізи пацієнта</a>
        </div>
    </li>
</ul>
<img class="mockup" src="css/main.jpg">
<img class="mockup" src="css/footer.jpg">
</body>
</html>