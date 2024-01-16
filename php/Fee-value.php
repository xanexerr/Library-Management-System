<?php
require('../connection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.0"></script>
</head>

<body>
    <?php

    require('../connection.php');
    session_start();

    if ($_SESSION["role"] !== 'admin') {
        echo '<script>';
        echo 'Swal.fire({
            title: "คุณไม่มีสิทธิเข้าถึง!",
            icon: "error",
            confirmButtonText: "ตกลง"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "../index.php";
            }
        });';
        echo '</script>';
        exit();
    }

    if (isset($_POST['fee'])) {
        $fee = $_POST['fee'];

        $fee = mysqli_real_escape_string($connection, $fee);

        $alterQuery = "ALTER TABLE `borrow` MODIFY `feeperday` INT(11) NOT NULL DEFAULT '$fee'";
        $updateQuery = "UPDATE `borrow` SET `feeperday` = '$fee'";
        if (($connection->query($updateQuery) === TRUE) && ($connection->query($alterQuery) === TRUE)) {
            echo '<script>';
            echo 'Swal.fire({
                title: "แก้ไขค่าปรับต่อวันแล้ว",
                icon: "success",
                confirmButtonText: "ตกลง"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "../Fee.php";
                }
            });';
            echo '</script>';
        } else {
            echo "เกิดข้อผิดพลาด : " . $connection->error;
        }
    }
    ?>
</body>

</html>