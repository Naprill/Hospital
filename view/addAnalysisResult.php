<?php
spl_autoload_register(function ($class_name) {
    include "../model/". $class_name . '.php';
});

$selectObj = new Select();
$addresses = $selectObj->selectAll("Address");
$diagnoses = $selectObj->selectAll("Diagnoses");
$patients = $selectObj->selectAll("Patients");
$current_analysis_name = $selectObj->selectAnalysis($_GET['analysis_id']);
$parameters = $selectObj->selectParameters($_GET['analysis_id']);

if (isset($_POST['send'])){
    $insertObj = new Insert();

    $newPatientId = 0;
    $patient_name = trim($_POST['patient_name']);
    $patient_id = trim($_POST['patient_id']);

    if(is_numeric($_POST['patient_id'])){
        // додаємо аналіз наявного у базі пацієнта
        $newPatientId = $_POST['patient_id'];
    }
    else{
        // додаємо аналіз нового пацієнта
        $newPatientId = $insertObj->insertPatient(
            $_POST['patient_name'],
            $_POST['birthdate'],
            $_POST['sex'],
            $_POST['address']
        );
    }

    //перевірка чи є ід
    $newOrderId = 0;
    if($newPatientId){
        $newOrderId = $insertObj->insertOrder(
            $newPatientId,
            $_POST['diagnosis'],
            $_GET['analysis_id'], ///!!!
            $_POST['cover_diagnosis'],
            $_POST['receiving_date'],
            $_POST['completion_date'],
            $_POST['place']
        );
    }
    else{
        echo "Patient was not added<br>";
    }

    if($newOrderId){
        foreach ($parameters as $parameter){
            if(isset($_POST[$parameter['parameter_id']])){
                $result = $_POST[$parameter['parameter_id']];
                $insertObj->insertResult(
                    $parameter['parameter_id'],
                    $newOrderId,
                    $result
                );
            }
        }
    }
    else{
        echo "Order was not added";
    }


}
?>

<!DOCTYPE html>
<html>
  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <link href='http://fonts.googleapis.com/css?family=Nunito:400,300' rel='stylesheet' type='text/css'>

      <link rel="stylesheet" href="../css/style.css">

      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

      <script type="text/javascript" src="../js/datalist.js"></script>

    <title>Чернівецька обласна лікарня</title>
  </head>
  <body >

  <?php include "header.php"; ?>

  <h2>Додавання аналізу</h2>

<form action="addAnalysisResult.php?analysis_id=<?php echo $_GET['analysis_id']; ?>" method="post">

    <div class="info">
        <label>Пацієнт:
            <input id="list" name="patient_name" list="name">
            <input id="list-hidden" type="hidden" name="patient_id" >
            <datalist id="name">
                <?php foreach ($patients as $patient_name) : ?>
                    <option data-value='<?php echo $patient_name['patient_id']; ?>'><?php echo $patient_name['patient_name']; ?></option>;
                <?php endforeach; ?>
            </datalist>
        </label>
    </div>

  <div class="info">
    <label>Дата народження: <input name="birthdate" type="date"></label>
  </div>
  <div class="info">
    <label>Стать:
        <select name="sex">
                <option value='Female'>Жіноча</option>
                <option value='Male'>Чоловіча</option>
        </select>
    </label>
  </div>
    <div class="info">
      <label>Місце проживання:
          <select name="address">
              <?php foreach ($addresses as $address) : ?>
                  <option value='<?php echo $address['address_id']; ?>'><?php echo $address['address_name']; ?></option>;
              <?php endforeach; ?>
          </select>
      </label>
    </div>
    <div class="info">
      <label>Дата отримання матеріалу лабораторією: <input name="receiving_date" type="date"></label>
    </div>


    <table>
      <caption><?php echo $current_analysis_name; ?>
      </caption>
      <thead>
        <tr>
          <th class="subheader" scope="col" >Показник</th>
          <th class="subheader" scope="col" >Результат</th>
          <th class="subheader" scope="col" >Одиниці</th>
          <th class="subheader" scope="col" >Референтний інтервал</th>
        </tr>
      </thead>

      <tbody>

        <?php foreach ($parameters as $parameter) : ?>
          <tr>
            <th scope="row"> <?php echo $parameter['parameter_name'] ?> </th>
            <td> <input name="<?php echo $parameter['parameter_id']; ?>" title="<?php echo $parameter['parameter_name']; ?>" min="0" step="0.1" value="0" type="number"> </td>
            <td> <?php echo $parameter['unit']?> </td>
              <td>
                  <?php
                  if($parameter['norm_min']==0 && $parameter['norm_max']==0)
                      echo "відсутній";
                  else if($parameter['norm_max']==1000)
                      echo " > ".$parameter['norm_min'];
                  else if($parameter['norm_min']==0)
                      echo " < ".$parameter['norm_max'];
                  else
                      echo "від ".$parameter['norm_min']." до ".$parameter['norm_max'];
                  ?>
              </td>
          </tr>
        <?php endforeach; ?>


      </tbody>
    </table>

    <div class="info">
        <label>Заключення:
            <select name="diagnosis">
            <?php foreach ($diagnoses as $diagnosis) : ?>
                <option value='<?php echo $diagnosis['diagnosis_id']; ?>'><?php echo $diagnosis['diagnosis_name']; ?></option>;
            <?php endforeach; ?>
            </select>
        </label>
    </div>
    <div class="info">
        <label>Супутній діагноз: <input class="input70" name="cover_diagnosis" type="text"></label>
    </div>
    <div class="info">
        <label>Дата закінчення аналізу: <input name="completion_date" type="date"></label>
    </div>
    <div class="info">
        <label>Місце здачі аналізу: <input name="place" type="text"></label>
    </div>
    <input type="submit" name="send" value="Зберегти у базі даних" required/>
</form>
  <img class="mockup" src="../css/footer.jpg">
  </body>
</html>
