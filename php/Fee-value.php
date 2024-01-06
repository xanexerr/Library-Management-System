<?php
require('../connection.php');
if (isset($_POST['fee'])) {
    $fee = $_POST['fee'];

    $fee = mysqli_real_escape_string($connection, $fee);

    $alterQuery = "ALTER TABLE `borrow` MODIFY `feeperday` INT(11) NOT NULL DEFAULT '$fee'";
    $updateQuery = "UPDATE `borrow` SET `feeperday` = '$fee'";
    if (($connection->query($updateQuery) === TRUE) && ($connection->query($alterQuery) === TRUE)) {
        echo '<script>';
        echo 'alert("แก้ไขค่าปรับต่อวันแล้ว");';
        echo "window.location.href = '../Fee.php';";
        echo '</script>';
    } else {
        echo "เกิดข้อผิดพลาด : " . $connection->error;
    }
}

?>