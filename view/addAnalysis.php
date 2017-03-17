<?php
spl_autoload_register(function ($class_name) {
    include "../model/". $class_name . '.php';
});
if (isset($_POST['send'])){
    $insertObj = new Insert();
    $insertObj->insertAnalysis($_POST['analysis']);
    header("Location: addParameter.php"); exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="../css/style.css">

    <title>Чернівецька обласна лікарня</title>
</head>
<body >

<?php include "header.php"; ?>

<form action="addAnalysis.php" method="post">

    <label>Назва аналізу:
        <input name="analysis" title="analysis" type="text">
    </label>

    <input type="submit" name="send" value="Створити" required/>
</form>
</body>
</html>
