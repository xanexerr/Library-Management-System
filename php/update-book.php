<?php
require_once('../connection.php'); // Ensure the connection is established

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch data from the form
    $book_id = $_POST['book_id'];
    $user_fname = $_POST['book_name'];
    $user_lname = $_POST['author'];
    $username = $_POST['publisher'];
    $user_type = $_POST['book_type'];
    $bookvalue = $_POST['bookvalue'];

    // Prepare the SQL statement with placeholders
    $stmt = $conn->prepare("UPDATE books SET book_name = ?, author = ?, publisher = ?, type_id = ?, bookvalue = ? WHERE book_id = ?");

    // Bind parameters to the statement
    $stmt->bindParam(1, $user_fname);
    $stmt->bindParam(2, $user_lname);
    $stmt->bindParam(3, $username);
    $stmt->bindParam(4, $user_type);
    $stmt->bindParam(5, $bookvalue);
    $stmt->bindParam(6, $book_id);

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "<script>
                alert('แก้ไขข้อมูลหนังสือสำเร็จ!');
                window.location.href = '../manage-book.php';
                </script>";
        exit();
    } else {
        echo "<script>
                alert('มีข้อผิดพลาดในการแก้ไขข้อมูล:! " . $stmt->errorInfo()[2];
        "');
                window.location.href = '../manage-book.php';
                </script>";
    }

    // Close the statement and the database connection
    $stmt = null;
    $conn = null;
}
?>