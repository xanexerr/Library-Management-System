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
    require_once('../connection.php'); // Ensure the connection is established
    
    session_start();
    if (!isset($_SESSION["username"])) {
        echo '<script>';
        echo 'Swal.fire("คุณยังไม่ได้เข้าสู่ระบบ", { icon: "warning" }).then(() => { window.location.href = "login.php"; });';
        echo '</script>';
        exit();
    } else if ($_SESSION["role"] !== 'librarian' && $_SESSION["role"] !== 'admin') {
        echo '<script>';
        echo 'Swal.fire("คุณไม่มีสิทธิเข้าถึง!", { icon: "error" }).then(() => { window.location.href = "index.php"; });';
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
        $successMessage = "ลบผู้ใช้สำเร็จ!";
        $redirectURL = ($_SESSION['role'] == 'admin') ? '../admin-main.php' : '../librarian-users.php';

        echo '<script>';
        echo 'Swal.fire({ title: "' . $successMessage . '", icon: "success" }).then(() => { window.location.href = "' . $redirectURL . '"; });';
        echo '</script>';
    } else {
        $errorMessage = "มีข้อผิดพลาดในการลบข้อมูล: " . $stmt->errorInfo()[2];
        echo 'Swal.fire({ title: "' . $errorMessage . '", icon: "error" });';
    }

    // Close the statement and the database connection
    $stmt = null;
    $conn = null;
    ?>




</body>

</html>