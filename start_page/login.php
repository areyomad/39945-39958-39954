<?php
session_start();
include_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $haslo = $_POST['haslo'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($haslo, $user['password'])) {
        $_SESSION['login'] = $user['login'];
        header("Location: /account/welcome.php");
        exit();
    } else {
        $error = "Nieprawidłowy login lub hasło.";
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
<body class="bg-light">
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
  <div class="card shadow p-4 w-100" style="max-width: 400px;">
    <h3 class="mb-4 text-center">Logowanie</h3>
    <?php if (isset($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label for="login" class="form-label">Login</label>
        <input type="text" class="form-control" id="login" name="login" required>
      </div>
      <div class="mb-3">
        <label for="haslo" class="form-label">Hasło</label>
        <input type="password" class="form-control" id="haslo" name="haslo" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Zaloguj się</button>
    </form>
    <div class="text-center mt-3">
      <a href="register.php">Nie masz konta? Zarejestruj się</a>
    </div>
  </div>
</div>
</body>
</html>
