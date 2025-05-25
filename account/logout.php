<?php
session_start();
session_destroy();
header("Location: ../start_page/login.php");
exit();
?>