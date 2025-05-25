<?php
session_start();
include_once __DIR__ . '/../db.php';

if (!isset($_SESSION['login'])) {
    header("Location: /start_page/login.php");
    exit();
}

$login = $_SESSION['login'];

// Pobierz ID użytkownika
$stmt = $conn->prepare("SELECT id FROM users WHERE login = ?");
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'] ?? null;

// Obsługa usuwania
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM shopping_lists WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $delete_id, $user_id);
    $stmt->execute();
    header("Location: shopping_list.php?date=" . urlencode($_GET['date']));
    exit();
}

// Obsługa edycji
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'], $_POST['edit_item'])) {
    $edit_id = intval($_POST['edit_id']);
    $edit_item = trim($_POST['edit_item']);
    if ($edit_item !== '') {
        $stmt = $conn->prepare("UPDATE shopping_lists SET item = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("sii", $edit_item, $edit_id, $user_id);
        $stmt->execute();
    }
    header("Location: shopping_list.php?date=" . urlencode($_POST['date']));
    exit();
}

// Dodawanie nowej pozycji
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['amount'], $_POST['unit'], $_POST['item_name']) && !isset($_POST['edit_id'])) {
    $date = $_POST['date'];
    $amount = intval($_POST['amount']);
    $unit = $_POST['unit'];
    $item_name = trim($_POST['item_name']);
    $item = "$amount $unit $item_name";

    if ($item_name !== '') {
        $stmt = $conn->prepare("INSERT INTO shopping_lists (user_id, date, item) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $date, $item);
        $stmt->execute();
    }
}

// Ustawienie daty
$selected_date = $_GET['date'] ?? date('Y-m-d');

// Pobranie listy zakupów
$stmt = $conn->prepare("SELECT id, item FROM shopping_lists WHERE user_id = ? AND date = ?");
$stmt->bind_param("is", $user_id, $selected_date);
$stmt->execute();
$items_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Lista zakupów</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Lista zakupów na wybrany dzień</h2>

    <form class="row g-3 mb-4" method="GET">
        <div class="col-auto">
            <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($selected_date) ?>" required>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Pokaż listę</button>
        </div>
    </form>

    <form class="row g-3 mb-5" method="POST">
        <input type="hidden" name="date" value="<?= htmlspecialchars($selected_date) ?>">

        <div class="col-md-2">
            <input type="number" name="amount" class="form-control" placeholder="Ilość" min="1" required>
        </div>

        <div class="col-md-3">
            <select name="unit" class="form-select" required>
                <option value="opakowanie">opakowanie</option>
                <option value="porcja">porcja</option>
                <option value="gramów">gramów</option>
                <option value="kilogramów">kilogramów</option>
                <option value="szt.">szt.</option>
            </select>
        </div>

        <div class="col-md-5">
            <input type="text" name="item_name" class="form-control" placeholder="Nazwa produktu" required>
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-success w-100">Dodaj</button>
        </div>
    </form>

    <h4>Zakupy na <?= htmlspecialchars($selected_date) ?>:</h4>
    <ul class="list-group">
        <?php while ($row = $items_result->fetch_assoc()): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><?= htmlspecialchars($row['item']) ?></span>
                <div class="btn-group btn-group-sm" role="group">
                    <button class="btn btn-outline-secondary" onclick="showEditForm(<?= $row['id'] ?>, '<?= htmlspecialchars($row['item'], ENT_QUOTES) ?>')">📝 Edytuj</button>
                    <a href="?delete=<?= $row['id'] ?>&date=<?= urlencode($selected_date) ?>" class="btn btn-outline-danger" onclick="return confirm('Czy na pewno usunąć ten produkt?')">🗑️ Usuń</a>
                </div>
            </li>
        <?php endwhile; ?>
    </ul>

    <!-- Formularz edycji -->
    <form id="editForm" method="POST" class="row g-3 mt-4 d-none">
        <input type="hidden" name="edit_id" id="edit_id">
        <input type="hidden" name="date" value="<?= htmlspecialchars($selected_date) ?>">
        <div class="col-md-8">
            <input type="text" name="edit_item" id="edit_item" class="form-control" required>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-warning w-100">Zapisz zmiany</button>
        </div>
    </form>

    <a href="/account/welcome.php" class="btn btn-secondary mt-4">Powrót</a>
</div>

<script>
    function showEditForm(id, item) {
        document.getElementById('editForm').classList.remove('d-none');
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_item').value = item;
        document.getElementById('edit_item').focus();
    }
</script>
</body>
</html>
