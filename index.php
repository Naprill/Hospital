<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>

    <!--<link rel="stylesheet" href="css/style.css">-->
    <style>
        div{
            padding: 50px 100px;
            border-radius: 10px;
            border: 5px solid #478ac5;
            font-size: larger;
        }
        h1{
            color: #298ac5;
        }
        ul {
            border-radius: 10px;
            list-style-type: none;
            margin: 0;
            padding: 0;
            width: 500px;
            /*background-color: #c1c8ff;*/
        }

        li a {
            display: block;
            color: #000;
            padding: 8px 16px;
            text-decoration: none;
        }

        /* Change the link color on hover */
        li a:hover {
            background-color: #478ac5;
            color: white;
        }
    </style>
    <title>Чернівецька обласна лікарня</title>
</head>
<body>
<div>
    <h1>Чернівецька обласна лікарня</h1>
    <ul>
        <p>Змінити шаблони</p>
        <li><a href="view/addAnalysis.php">Створити новий аналіз</a></li>
        <li><a href="view/addParameter.php">Додати новий параметр</a></li>
        <p>Зберегти результати</p>
        <!--TODO тут буде цикл php, який створить список всіх аналізів-->
        <li><a href="view/addAnalysisResult.php?analysis_id=1">Додати аналіз мікрофлори</a></li>
        <li><a href="view/addAnalysisResult.php?analysis_id=2">Додати аналіз пробний</a></li>
        <p>Пошук</p>
        <li><a href="view/viewAnalysis.php">Знайти аналіз мікрофлори</a></li>
        <li><a href="view/searchAnalysis.php">Знайти...</a></li>
        <li><a href="">Знайти аналіз крові</a></li>

    </ul>
</div>
</body>
</html>