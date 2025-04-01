<?php session_start(); ?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Witamy!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .welcome-box {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="welcome-box">
    <h1 class="mb-4">Witamy w DietApp!</h1>

    <?php if (isset($_SESSION['login'])): ?>
        <p class="mb-3">Zalogowany jako <strong><?= htmlspecialchars($_SESSION['login']) ?></strong></p>
        <a href="welcome.php" class="btn btn-success me-2">Przejdź dalej</a>
        <a href="logout.php" class="btn btn-danger">Wyloguj się</a>
    <?php else: ?>
        <a href="login.php" class="btn btn-primary btn-lg me-3">Zaloguj się</a>
        <a href="register.php" class="btn btn-outline-primary btn-lg">Zarejestruj się</a>
    <?php endif; ?>
</div>
</body>
</html>
