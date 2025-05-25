<?php
session_start();
include_once '../db.php';

if (!isset($_SESSION['login'])) {
    header("Location: /start_page/login.php");
    exit();
}

$login = $_SESSION['login'];
$date = $_GET['date'] ?? null;
if (!$date) {
    echo "Nieprawidłowa data.";
    exit();
}

// Pobierz ID użytkownika
$stmt = $conn->prepare("SELECT id FROM users WHERE login = ?");
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

// Pobierz produkty przypisane do dnia
$stmt = $conn->prepare("SELECT c.id as entry_id, c.amount, c.meal, p.* FROM calorie_entries c JOIN products p ON c.product_id = p.id WHERE c.user_id = ? AND c.date = ?");
$stmt->bind_param("is", $user_id, $date);
$stmt->execute();
$entries = $stmt->get_result();

$meals = ['śniadanie', 'drugie śniadanie', 'obiad', 'podwieczorek', 'kolacja'];
$grouped = [];
$summary = ['energy_kcal' => 0, 'protein' => 0, 'fat' => 0, 'carbohydrates' => 0];

while ($row = $entries->fetch_assoc()) {
    $meal = $row['meal'] ?? 'obiad';
    $grouped[$meal][] = $row;

    // Przelicz makroskładniki proporcjonalnie
    $factor = $row['amount'] / 100.0;
    $summary['energy_kcal'] += $row['energy_kcal'] * $factor;
    $summary['protein'] += $row['protein'] * $factor;
    $summary['fat'] += $row['fat'] * $factor;
    $summary['carbohydrates'] += $row['carbohydrates'] * $factor;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Dzień: <?= $date ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .meal-header {
        background-color: #f0f0f0;
        padding: 10px;
        font-weight: bold;
        border: 1px solid #ddd;
    }
  </style>
</head>
<body class="bg-light">
<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Dzień: <?= $date ?></h3>
    <a href="calorie_calculator.php" class="btn btn-secondary">← Wróć do kalendarza</a>
  </div>

  <?php foreach ($meals as $meal): ?>
    <div class="mb-4">
      <div class="d-flex justify-content-between align-items-center meal-header">
        <span><?= ucfirst($meal) ?></span>
        <a href="products_list.php?date=<?= $date ?>&meal=<?= urlencode($meal) ?>" class="btn btn-sm btn-outline-success">+ Dodaj</a>
      </div>
      <?php if (!empty($grouped[$meal])): ?>
        <ul class="list-group">
          <?php foreach ($grouped[$meal] as $item): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <?= htmlspecialchars($item['name']) ?> (<?= $item['amount'] ?>g/ml)
              <div>
                  <a href="meal_edit.php?id=<?= $item['entry_id'] ?>&date=<?= $date ?>" class="btn btn-sm btn-outline-primary">Edytuj</a>

                <a href="delete_entry.php?id=<?= $item['entry_id'] ?>&date=<?= $date ?>" class="btn btn-sm btn-outline-danger">Usuń</a>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <div class="text-muted mt-1 ms-2">Brak produktów</div>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>

  <div class="card p-3">
    <h5>Podsumowanie dnia:</h5>
    <ul class="list-group">
      <li class="list-group-item">Kalorie: <?= round($summary['energy_kcal']) ?> kcal</li>
      <li class="list-group-item">Białko: <?= round($summary['protein'], 1) ?> g</li>
      <li class="list-group-item">Tłuszcz: <?= round($summary['fat'], 1) ?> g</li>
      <li class="list-group-item">Węglowodany: <?= round($summary['carbohydrates'], 1) ?> g</li>
    </ul>
  </div>
</div>
</body>
</html>
