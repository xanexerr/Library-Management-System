<?php
session_start();
require 'connection.php';

$username = $connection->real_escape_string($_POST['username']);
$password = $connection->real_escape_string($_POST['password']);

$strSQL = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = $connection->query($strSQL);

if (!$result || $result->num_rows === 0) {
    echo '<script>alert("เกิดข้อผิดพลาด! กรุณากรอกใหม่อีกครั้ง");</script>';
    echo '<script>window.location.href = "login.php";</script>';
    exit;
} else {
    $objResult = $result->fetch_assoc();
    $_SESSION["username"] = $objResult["username"];
    $_SESSION["role"] = $objResult["role"];
    $_SESSION["user_fname"] = $objResult["user_fname"];
    $_SESSION["user_lname"] = $objResult["user_lname"];
    session_write_close();
    if ($objResult["role"] == "admin") {
        header("location: admin-main.php");
    } else {
        header("location: librarain_main.php");
    }
}
$connection->close();
?>