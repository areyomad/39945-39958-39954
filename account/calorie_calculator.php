<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: ../start_page/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Kalendarz kalorii</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .calendar { display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px; }
    .day { border: 1px solid #ccc; padding: 10px; text-align: center; cursor: pointer; }
    .day:hover { background-color: #e9ecef; }
  </style>
</head>
<body class="bg-light">
<div class="container py-5">
  <h2 class="mb-4">Wybierz dzień</h2>
  <div class="calendar">
    <?php
      $daysInMonth = date('t');
      $year = date('Y');
      $month = date('m');
      for ($day = 1; $day <= $daysInMonth; $day++) {
          $date = "$year-$month-$day";
          echo "<a href='calorie_day.php?date=$date' class='day'>$day</a>";
      }
    ?>
  </div>
  <a href="../account/welcome.php" class="btn btn-secondary mt-4">Powrót do menu</a>
</div>
</body>
</html>
