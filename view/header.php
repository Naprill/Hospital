<?php
$selectObj = new Select();
$analyzes = $selectObj->selectAll("Analyzes");
?>
<a href="../index.php"><img src="../css/header.jpg"></a>
  <ul>
      <li class="dropdown">
          <a href="javascript:void(0)" class="dropdown_button">Змінити шаблони</a>
          <div class="dropdown-content">
              <a  href="addAnalysis.php">Створити новий шаблон додавання аналізу</a>
              <a  href="#">Редагувати шаблон додавання аналізу</a>
          </div>
      </li>
      <li class="dropdown">
          <a href="javascript:void(0)" class="dropdown_button">Зберегти результати</a>
          <div class="dropdown-content">
              <?php foreach ($analyzes as $analysis) : ?>
                <a href="addAnalysisResult.php?analysis_id=<?php echo $analysis['analysis_id']; ?>"><?php echo $analysis['analysis_name']; ?></a>
            <?php endforeach; ?>
        </div>
    </li>
    <li class="dropdown">
        <a href="javascript:void(0)" class="dropdown_button">Пошук</a>
        <div class="dropdown-content">
            <a  href="searchAnalysis.php">Знайти аналізи</a>
            <a  href="searchPatient.php">Знайти аналізи пацієнта</a>
        </div>
    </li>
</ul>