<?php
require_once('../connection.php'); // Ensure the connection is established

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch data from the form
    $book_id = $_POST['book_id'];
    $book_name = $_POST['book_name'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $book_type = $_POST['book_type'];
    $bookvalue = $_POST['bookvalue'];

    // Prepare the SQL statement with placeholders
    $stmt = $conn->prepare("UPDATE books SET book_name = ?, author = ?, publisher = ?, type_id = ?, bookvalue = ? WHERE book_id = ?");

    // Bind parameters to the statement
    $stmt->bindParam(1, $book_name);
    $stmt->bindParam(2, $author);
    $stmt->bindParam(3, $publisher);
    $stmt->bindParam(4, $book_type);
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