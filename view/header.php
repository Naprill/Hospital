<?php
$selectObj = new Select();
$analyzes = $selectObj->selectAll("Analyzes");
?>
<div class="mockup">
    <a href="../index.php">
        <img src="../css/header.jpg" style="width: 100%;">
    </a>
</div>
  <ul>
      <li class="dropdown">
          <a href="javascript:void(0)" class="dropdown_button">Редагування шаблонів</a>
          <div class="dropdown-content">
              <a  href="addAnalysis.php">Редагування шаблону додавання аналізу</a>
              <a  href="addDiagnosis.php">Редагування діагнозів</a>
          </div>
      </li>
      <li class="dropdown">
          <a href="javascript:void(0)" class="dropdown_button">Зберігання результатів аналізів</a>
          <div class="dropdown-content">
              <?php foreach ($analyzes as $analysis) : ?>
                <a href="addAnalysisResult.php?analysis_id=<?php echo $analysis['analysis_id']; ?>"><?php echo $analysis['analysis_name']; ?></a>
            <?php endforeach; ?>
        </div>
    </li>
    <li class="dropdown">
        <a href="javascript:void(0)" class="dropdown_button">Пошук</a>
        <div class="dropdown-content">
            <a  href="searchAnalysis.php">Пошук за критеріями</a>
            <a  href="searchPatient.php">Пошук аналізів пацієнта</a>
        </div>
    </li>
      <li class="dropdown">
          <a href="javascript:void(0)" class="dropdown_button">Статистичні звіти</a>
          <div class="dropdown-content">
              <a  href="generalReport.php">Загальний звіт</a>
          </div>
      </li>
      <li class="dropdown">
          <a href="javascript:void(0)" class="dropdown_button">Статистичні діаграми</a>
          <div class="dropdown-content">
              <a  href="patientsDiagnosisDiagram.php">Діаграма розподілу пацієнтів по діагнозу</a>
              <a  href="districtDiagnosisDiagram.php">Порівняльна діаграма розподілу захворюваності по районах</a>
          </div>
      </li>
</ul>