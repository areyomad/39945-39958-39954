<?php
$host = 'sql160.lh.pl';
$user = 'serwer359382_dietapp';
$password = '!FTProot359382';
$database = 'serwer359382_dietapp';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die('B��d po��czenia z baz� danych: ' . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
