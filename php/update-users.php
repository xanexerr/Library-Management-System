<?php
require_once("../connection.php");
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are filled
    $user_id = ($_POST['user_id']);
    $username = htmlspecialchars($_POST['username']);
    $user_fname = htmlspecialchars($_POST['user_fname']);
    $user_lname = htmlspecialchars($_POST['user_lname']);
    $role = htmlspecialchars($_POST['role']);

    $updatestatus = "UPDATE users
                SET 
                    username = ?,
                    user_fname = ?,
                    user_lname = ?,
                    role  = ?
                WHERE user_id = ?";

    $stmt = $connection->prepare($updatestatus);
    $stmt->bind_param(
        "ssssi",
        $username,
        $user_fname,
        $user_lname,
        $role,
        $user_id
    );

    if ($stmt->execute()) {
        if ($_SESSION['role'] == 'admin') {
            echo "<script>
                alert('แก้ไขข้อมูลสำเร็จ!');
                window.location.href = '../admin-main.php';
                </script>";
            exit();
        } elseif ($_SESSION['role'] == 'librarian') {
            echo "<script>
                alert('แก้ไขข้อมูลสำเร็จ!');
                window.location.href = '../librarian-users.php';
                </script>";
            exit();
        }

    } else {
        echo "<script>
                alert('มีข้อผิดพลาดในการแก้ไขข้อมูล:! " . $stmt->error;
        "');
                window.location.href = '../manage-book.php';
                </script>";
    }

} else {
    echo "Form submission error.";
}
?>