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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch data from the form
    $user_fname = $_POST['book_name'];
    $user_lname = $_POST['author'];
    $username = $_POST['publisher'];
    $user_type = $_POST['book_type'];
    $bookvalue = $_POST['bookvalue'];

    // Prepare the SQL statement with placeholders
    $stmt = $conn->prepare("INSERT INTO books (book_name, author, publisher, type_id, bookvalue) VALUES (?, ?, ?, ?, ?)");

    // Bind parameters to the statement
    $stmt->bindParam(1, $user_fname);
    $stmt->bindParam(2, $user_lname);
    $stmt->bindParam(3, $username);
    $stmt->bindParam(4, $user_type);
    $stmt->bindParam(5, $bookvalue);

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo '<script>';
        echo 'alert("เพิ่มหนังสือสำเร็จ!");';
        echo "window.location.href = '../lb-book.php';";
        echo '</script>';
    } else {
        echo "มีข้อผิดพลาดในการเพิ่มข้อมูล: " . $stmt->errorInfo()[2];
    }

    // Close the statement and the database connection
    $stmt = null;
    $conn = null;
}
?>