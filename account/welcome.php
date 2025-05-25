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
  <title>Panel główny</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h2 class="mb-4">Witaj, <?= htmlspecialchars($_SESSION['login']) ?>!</h2>
  <div class="d-grid gap-2 col-6 mx-auto">
    <a href="calorie_calculator.php" class="btn btn-outline-primary btn-lg">Kalkulator kalorii</a>
    <a href="add_product.php" class="btn btn-outline-secondary btn-lg">Dodaj produkt</a>
    <a href="products_list.php" class="btn btn-outline-success btn-lg">Lista produktów</a>
    <a href="shopping_list.php" class="btn btn-outline-warning btn-lg">Lista zakupów</a>
    <a href="logout.php" class="btn btn-danger btn-lg">Wyloguj się</a>
  </div>
</div>
</body>
</html>