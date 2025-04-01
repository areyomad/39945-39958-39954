<?php include 'db.php'; session_start(); ?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $haslo = $_POST['haslo'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $uzytkownik = $result->fetch_assoc();

    if ($uzytkownik && password_verify($haslo, $uzytkownik['haslo'])) {
        $_SESSION['login'] = $uzytkownik['login'];
        header("Location: welcome.php");
    } else {
        $blad = "Nieprawidłowy login lub hasło.";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logowanie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
<h2>Logowanie</h2>
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
    <button type="submit" class="btn btn-success">Zaloguj</button>
</form>
<p class="mt-3">
    Nie masz konta?
    <a href="register.php" class="btn btn-primary">Zarejestruj się</a>
</p>

</body>
</html>
