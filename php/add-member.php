<?php
require_once('../connection.php'); // Ensure the connection is established

session_start();
require('../connection.php');
if (!isset($_SESSION["username"])) {
    echo '<script>';
    echo 'alert("คุณยังไม่ได้เข้าสู่ระบบ");';
    echo 'window.location.href = "login.php";';
    echo '</script>';
    exit();
} else if ($_SESSION["role"] !== 'librarian' && $_SESSION["role"] !== 'admin') {
    echo '<script>';
    echo 'alert("คุณไม่มีสิทธิเข้าถึง!");';
    echo 'window.location.href = "index.php";';
    echo '</script>';
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch data from the form
    $user_fname = $_POST['user_fname'];
    $user_lname = $_POST['user_lname'];
    $username = $_POST['username'];
    $user_type = $_POST['user_type'];

    // Prepare the SQL statement with placeholders
    $stmt = $conn->prepare("INSERT INTO users(user_fname, user_lname, username, role) VALUES (?, ?, ?, ?)");

    // Bind parameters to the statement
    $stmt->bindParam(1, $user_fname);
    $stmt->bindParam(2, $user_lname);
    $stmt->bindParam(3, $username);
    $stmt->bindParam(4, $user_type);


    // Execute the prepared statement
    if ($stmt->execute()) {
        if ($_SESSION['role'] == 'admin') {
            echo '<script>';
            echo 'alert("เพิ่มผู้ใช้สำเร็จ!");';
            echo "window.location.href = '../admin-main.php';";
            echo '</script>';
        } elseif ($_SESSION['role'] == 'librarian') {
            echo '<script>';
            echo 'alert("เพิ่มผู้ใช้สำเร็จ!");';
            echo "window.location.href = '../librarian-users.php';";
            echo '</script>';
        }
    } else {
        echo "มีข้อผิดพลาดในการเพิ่มข้อมูล: " . $stmt->errorInfo()[2];
    }

    // Close the statement and the database connection
    $stmt = null;
    $conn = null;
}
?>