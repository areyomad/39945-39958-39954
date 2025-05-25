<?php
session_start();
include_once '../db.php';

if (!isset($_SESSION['login'])) {
    header("Location: /start_page/login.php");
    exit();
}

$entry_id = $_GET['id'] ?? null;
$date = $_GET['date'] ?? null;

if (!$entry_id || !$date) {
    echo "Brak wymaganych danych.";
    exit();
}

// Pobierz dane wpisu i produktu
$stmt = $conn->prepare("SELECT c.*, p.name, p.unit FROM calorie_entries c JOIN products p ON c.product_id = p.id WHERE c.id = ?");
$stmt->bind_param("i", $entry_id);
$stmt->execute();
$entry = $stmt->get_result()->fetch_assoc();

if (!$entry) {
    echo "Nie znaleziono wpisu.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount']);
    $meal = $_POST['meal'];

    $stmt = $conn->prepare("UPDATE calorie_entries SET amount = ?, meal = ? WHERE id = ?");
    $stmt->bind_param("dsi", $amount, $meal, $entry_id);
    $stmt->execute();

    header("Location: calorie_day.php?date=" . urlencode($date));
    exit();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edycja posiłku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="mb-4">Edycja: <?= htmlspecialchars($entry['name']) ?></h3>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Ilość (<?= htmlspecialchars($entry['unit']) ?>):</label>
            <input type="number" name="amount" value="<?= $entry['amount'] ?>" class="form-control" step="0.01" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Posiłek:</label>
            <select name="meal" class="form-select">
                <?php
                $meals = ['śniadanie', 'drugie śniadanie', 'obiad', 'podwieczorek', 'kolacja'];
                foreach ($meals as $m):
                    $selected = $entry['meal'] === $m ? 'selected' : '';
                    echo "<option value=\"$m\" $selected>$m</option>";
                endforeach;
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Zapisz zmiany</button>
        <a href="calorie_day.php?date=<?= urlencode($date) ?>" class="btn btn-secondary ms-2">Anuluj</a>
    </form>
</div>
</body>
</html>