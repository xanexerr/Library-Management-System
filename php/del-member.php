<?php
require_once('../connection.php'); // Ensure the connection is established

session_start();
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

$user_id = $_GET['id'];

$stmtBorrow = $conn->prepare("DELETE FROM borrow WHERE user_id = ?");
$stmtBorrow->bindParam(1, $user_id);
$stmtBorrow->execute();


$stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
$stmt->bindParam(1, $user_id);
if ($stmt->execute()) {
    if ($_SESSION['role'] == 'admin') {
        echo '<script>';
        echo 'alert("ลบผู้ใช้สำเร็จ!");';
        echo "window.location.href = '../admin-main.php';";
        echo '</script>';
    } elseif ($_SESSION['role'] == 'librarian') {
        echo '<script>';
        echo 'alert("ลบผู้ใช้สำเร็จ!");';
        echo "window.location.href = '../librarian-users.php';";
        echo '</script>';
    }
} else {
    echo "มีข้อผิดพลาดในการเพิ่มข้อมูล: " . $stmt->errorInfo()[2];
}

// Close the statement and the database connection
$stmt = null;
$conn = null;

?>