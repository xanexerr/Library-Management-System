<?php
session_start();
session_destroy();
header("Location: login.php");
// header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
?>