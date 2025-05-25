<?php
session_start();
include_once '../db.php';

if (!isset($_SESSION['login'])) {
    header("Location: /start_page/login.php");
    exit();
}

$login = $_SESSION['login'];

$date = $_GET['date'] ?? null;
$meal = $_GET['meal'] ?? null;

$search = $_GET['search'] ?? '';
$query = "SELECT * FROM products WHERE name LIKE ?";
$stmt = $conn->prepare($query);
$like = "%{$search}%";
$stmt->bind_param("s", $like);
$stmt->execute();
$results = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Lista produktów</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Lista produktów</h3>
        <div>
            <a href="add_product.php" class="btn btn-outline-success me-2">+ Dodaj nowy produkt</a>
            <a href="/account/welcome.php" class="btn btn-outline-primary">← Menu</a>
        </div>
    </div>

    <form method="get" class="input-group mb-4">
        <input type="text" name="search" class="form-control" placeholder="Szukaj produktu..." value="<?= htmlspecialchars($search) ?>">
        <?php if ($date): ?>
            <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">
        <?php endif; ?>
        <?php if ($meal): ?>
            <input type="hidden" name="meal" value="<?= htmlspecialchars($meal) ?>">
        <?php endif; ?>
        <button class="btn btn-primary" type="submit">Szukaj</button>
    </form>

    <div class="list-group">
        <?php while ($row = $results->fetch_assoc()): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div><?= htmlspecialchars($row['name']) ?></div>
                <div>
                    <?php if ($date && $meal): ?>
                        <a href="product_details.php?id=<?= $row['id'] ?>&date=<?= urlencode($date) ?>&meal=<?= urlencode($meal) ?>" class="btn btn-sm btn-outline-success">Dodaj</a>
                    <?php else: ?>
                        <a href="product_details.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-info">Szczegóły</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>
