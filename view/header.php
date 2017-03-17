<?php
$selectObj = new Select();
$analyzes = $selectObj->selectAll("Analyzes");
?>
<h1><a href="../index.php">Чернівецька обласна лікарня</a></h1>
  <ul>
      <li class="dropdown">
          <a href="javascript:void(0)" class="dropdown_button">Змінити шаблони</a>
          <div class="dropdown-content">
              <a href="addAnalysis.php">Створити новий аналіз</a>
              <a href="addParameter.php">Додати новий параметр</a>
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
            <a href="searchAnalysis.php">Знайти аналіз</a>
            <a href="searchPatient.php">Знайти пацієнта</a>
        </div>
    </li>
</ul>