<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Witaj</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
<h2>Witaj, <?php echo htmlspecialchars($_SESSION['login']); ?>!</h2>
<a href="logout.php" class="btn btn-danger mt-3">Wyloguj się</a>
</body>
</html>
