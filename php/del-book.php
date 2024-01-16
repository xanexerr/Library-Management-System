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
        echo 'alert("คุณยังไม่ได้เข้าสู่ระบบ");';
        echo 'window.location.href = "login.php";';
        echo '</script>';
        exit();
    } else {
        if ($_SESSION["role"] !== 'librarian') {
            echo '<script>';
            echo 'alert("คุณไม่มีสิทธิเข้าถึง!");';
            echo 'window.location.href = "index.php";';
            echo '</script>';
            exit();
        }
    }

    $book_id = $_GET['id'];

    $stmtBorrow = $conn->prepare("DELETE FROM borrow WHERE book_id = ?");
    $stmtBorrow->bindParam(1, $book_id);

    if (!$stmtBorrow->execute()) {
        echo "Error deleting borrow records: " . $stmtBorrow->errorInfo()[2];
        exit();
    }

    $stmt = $conn->prepare("DELETE FROM books WHERE book_id = ?");
    $stmt->bindParam(1, $book_id);

    if ($stmt->execute()) {
        $successMessage = "ลบหนังสือสำเร็จ!";
        $redirectURL = '../manage-book.php';

        echo '<script>';
        echo 'Swal.fire({ title: "' . $successMessage . '", icon: "success" }).then(() => { window.location.href = "' . $redirectURL . '"; });';
        echo '</script>';
    } else {
        echo "<script>
                alert('มีข้อผิดพลาดในการลบข้อมูล:! " . $stmt->errorInfo()[2];
        "');
                window.location.href = '../manage-book.php';
                </script>";
    }

    // Close the statement and the database connection
    $stmt = null;
    $conn = null;

    ?>

</body>

</html>