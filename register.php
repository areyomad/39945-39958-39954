<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $haslo = password_hash($_POST['haslo'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (login, haslo) VALUES (?, ?)");
    $stmt->bind_param("ss", $login, $haslo);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit(); // ← konieczne po header()
    } else {
        $blad = "Błąd rejestracji: " . $stmt->error;
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rejestracja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
<h2>Rejestracja</h2>
<?php if (isset($blad)) echo "<div class='alert alert-danger'>$blad</div>"; ?>
<form method="POST">
    <div class="mb-3">
        <label for="login" class="form-label">Login</label>
        <input type="text" name="login" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="haslo" class="form-label">Hasło</label>
        <input type="password" name="haslo" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Zarejestruj</button>
</form>
<p class="mt-3">
    Masz już konto?
    <a href="login.php" class="btn btn-primary">Zaloguj się</a>
</p>

</body>
</html>
