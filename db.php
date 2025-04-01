<?php
$host = 'db';
$user = 'root';
$pass = 'root';
$dbname = 'uzytkownicy';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}
?>
