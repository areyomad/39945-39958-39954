<?php
session_start();
include_once '../db.php';

$id = $_GET['id'] ?? $_POST['product_id'] ?? null;
$date = $_GET['date'] ?? $_POST['date'] ?? date('Y-m-d');
$meal = $_GET['meal'] ?? $_POST['meal'] ?? null;

if (!$id) {
    echo "Nieprawidłowy identyfikator produktu.";
    exit();
}

// Pobranie produktu
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
if (!$product) {
    echo "Nie znaleziono produktu.";
    exit();
}

// Dodanie wpisu do kalendarza
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['amount'])) {
    $login = $_SESSION['login'];
    $stmt = $conn->prepare("SELECT id FROM users WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $user_id = $user['id'];

    $amount = floatval($_POST['amount']);

    $stmt = $conn->prepare("INSERT INTO calorie_entries (user_id, product_id, date, meal, amount) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iissd", $user_id, $id, $date, $meal, $amount);
    $stmt->execute();

    header("Location: calorie_day.php?date=" . urlencode($date));
    exit();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2><?= htmlspecialchars($product['name']) ?></h2>

    <?php if (!empty($product['image'])): ?>
        <img src="data:image/jpeg;base64,<?= base64_encode($product['image']) ?>" alt="Zdjęcie produktu" class="img-fluid mb-3" style="max-height: 200px;">
    <?php endif; ?>

    <ul class="list-group mb-3">
        <li class="list-group-item">Jednostka: <?= htmlspecialchars($product['unit']) ?></li>
        <li class="list-group-item">kcal: <?= $product['energy_kcal'] ?></li>
        <li class="list-group-item">Tłuszcz: <?= $product['fat'] ?>g</li>
        <li class="list-group-item">Nasycone: <?= $product['saturated_fat'] ?>g</li>
        <li class="list-group-item">Węglowodany: <?= $product['carbohydrates'] ?>g</li>
        <li class="list-group-item">Cukry: <?= $product['sugars'] ?>g</li>
        <li class="list-group-item">Błonnik: <?= $product['fiber'] ?>g</li>
        <li class="list-group-item">Białko: <?= $product['protein'] ?>g</li>
        <li class="list-group-item">Sól: <?= $product['salt'] ?>g</li>
    </ul>

    <?php if ($meal): ?>
    <form method="POST">
        <div class="mb-3">
            <label for="amount" class="form-label">Ilość (g/ml):</label>
            <input type="number" class="form-control" name="amount" id="amount" min="1" required>
        </div>
         <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <input type="hidden" name="meal" value="<?= htmlspecialchars($meal) ?>">
        <button type="submit" class="btn btn-success">Dodaj do dnia</button>
    </form>
    <?php endif; ?>

    <div class="mt-3">
        <a href="calorie_day.php?date=<?= urlencode($date) ?>" class="btn btn-secondary">← Powrót</a>
        <a href="/account/welcome.php" class="btn btn-outline-primary ms-2">← Powrót do menu</a>
    </div>
</div>
</body>
</html>
