<?php
session_start();
include_once '../db.php';

if (!isset($_SESSION['login'])) {
    header("Location: ../start_page/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $unit = $_POST['unit'];
    $kcal = $_POST['kcal'];
    $fat = $_POST['fat'];
    $saturated = $_POST['saturated'];
    $carbs = $_POST['carbs'];
    $sugar = $_POST['sugar'];
    $fiber = $_POST['fiber'];
    $protein = $_POST['protein'];
    $salt = $_POST['salt'];

    $imageData = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
    }

    $stmt = $conn->prepare("INSERT INTO products (name, unit, energy_kcal, fat, saturated_fat, carbohydrates, sugars, fiber, protein, salt, image)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssddddddddd", $name, $unit, $kcal, $fat, $saturated, $carbs, $sugar, $fiber, $protein, $salt, $null);
    $stmt->send_long_data(10, $imageData);
    $stmt->execute();

    header("Location: products_list.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Dodaj produkt</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2 class="mb-4">Dodaj produkt</h2>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3"><label class="form-label">Nazwa</label><input type="text" name="name" class="form-control" required></div>
    <div class="mb-3"><label class="form-label">Jednostka</label>
      <select name="unit" class="form-select">
        <option value="100g">100g</option>
        <option value="100ml">100ml</option>
        <option value="1 porcja">1 porcja</option>
        <option value="1 opakowanie">1 opakowanie</option>
      </select>
    </div>
    <div class="row">
      <div class="col"><label class="form-label">kcal</label><input type="number" step="0.01" name="kcal" class="form-control" required></div>
      <div class="col"><label class="form-label">Tłuszcz</label><input type="number" step="0.01" name="fat" class="form-control"></div>
      <div class="col"><label class="form-label">Nasycone</label><input type="number" step="0.01" name="saturated" class="form-control"></div>
    </div>
    <div class="row mt-3">
      <div class="col"><label class="form-label">Węglowodany</label><input type="number" step="0.01" name="carbs" class="form-control"></div>
      <div class="col"><label class="form-label">Cukry</label><input type="number" step="0.01" name="sugar" class="form-control"></div>
      <div class="col"><label class="form-label">Błonnik</label><input type="number" step="0.01" name="fiber" class="form-control"></div>
    </div>
    <div class="row mt-3">
      <div class="col"><label class="form-label">Białko</label><input type="number" step="0.01" name="protein" class="form-control"></div>
      <div class="col"><label class="form-label">Sól</label><input type="number" step="0.01" name="salt" class="form-control"></div>
    </div>
    <div class="mb-3 mt-3">
      <label class="form-label">Zdjęcie produktu (opcjonalnie)</label>
      <input type="file" name="image" accept="image/*" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Dodaj</button>
    <a href="/account/welcome.php" class="btn btn-outline-primary ms-2">← Powrót do menu</a>
  </form>
</div>
</body>
</html>
