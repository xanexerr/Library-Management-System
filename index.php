<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/app.ico" type="image/x-icon">
    <title>โปรแกรมยืมหนังสือ</title>
</head>

<?php
session_start();
require('connection.php');
if (!isset($_SESSION["username"])) {
    echo '<script>';
    echo 'alert("คุณยังไม่ได้เข้าสู่ระบบ");';
    echo 'window.location.href = "login.php";';
    echo '</script>';
    exit();
} else {
    if ($_SESSION["role"] == "admin") {
        header("location: admin-main.php");
    } else {
        header("location: librarian_main.php");
    }
}
?>

<body>

</body>
<?php include('script.php'); ?>

</html>