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
    } else {
        // Check for the appropriate role or any other necessary conditions
        if ($_SESSION["role"] !== 'librarian') {
            echo '<script>';
            echo 'Swal.fire("คุณไม่มีสิทธิเข้าถึง!", { icon: "error" }).then(() => { window.location.href = "index.php"; });';
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
        $book_id = $_POST['book_id'];

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
            echo '<script>';
            echo 'Swal.fire({ title: "แก้ไขหนังสือสำเร็จ!", icon: "success" }).then(() => { window.location.href = "../manage-book.php"; });';
            echo '</script>';
            exit();
        } else {
            echo '<script>';
            echo 'Swal.fire({ title: "มีข้อผิดพลาดในการเพิ่มข้อมูล: ' . $stmt->errorInfo()[2] . '", icon: "error" }).then(() => { window.location.href = "../manage-book.php"; });';
            echo '</script>';
            exit();
        }
    }

    // Additional code if needed
    
    ?>

</body>

</html>