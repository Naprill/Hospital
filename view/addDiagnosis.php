<?php
/**
 * Created by PhpStorm.
 * User: ніна
 * Date: 16.04.2017
 * Time: 7:56
 */
spl_autoload_register(function ($class_name) {
    include "../model/". $class_name . '.php';
});

if (isset($_POST['send1'])){
    $insertObj = new Insert();
    $insertObj->insertDiagnosis($_POST['diagnosis_new']);
}

if (isset($_POST['send2'])){
    $deleteObject = new Delete();
    $deleteObject->deleteDiagnosis(
        $_POST['diagnosis_delete']
    );
}

$database = new Database();
$diagnoses = $database->getRows("SELECT * FROM Diagnoses"); ///!!!!!!!

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

<h2>Додавання діагнозу</h2>

<form action="addDiagnosis.php" method="post">

    <div class="field">
        <label>Назва діагнозу:
            <input name="diagnosis_new" title="diagnosis_new" type="text">
        </label>
    </div>

    <input type="submit" name="send1" value="Додати" required/>
</form>

<h2>Видалення діагнозу</h2>
<form action="addDiagnosis.php" method="post">

    <div class="field">
        <label>Виберіть діагноз:
            <select name="diagnosis_delete">
                <?php foreach ($diagnoses as $diagnosis) : ?>
                    <option value='<?php echo $diagnosis['diagnosis_id']; ?>'><?php echo $diagnosis['diagnosis_name']; ?></option>;
                <?php endforeach; ?>
            </select>
        </label>
    </div>

    <input type="submit" name="send2" value="Видалити" required/>
</form>
<img class="mockup" src="../css/footer.jpg">
</body>
</html>
