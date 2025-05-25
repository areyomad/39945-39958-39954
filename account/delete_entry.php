<?php
session_start();
include_once '../db.php';

if (!isset($_SESSION['login'])) {
    header("Location: /start_page/login.php");
    exit();
}

$login = $_SESSION['login'];
$entry_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$date = $_GET['date'] ?? null;

if (!$entry_id || !$date) {
    echo "Nieprawidłowe dane.";
    exit();
}

// Pobierz ID użytkownika
$stmt = $conn->prepare("SELECT id FROM users WHERE login = ?");
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

// Usuń wpis
$stmt = $conn->prepare("DELETE FROM calorie_entries WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $entry_id, $user_id);
$stmt->execute();

header("Location: calorie_day.php?date=" . urlencode($date));
exit();
?>