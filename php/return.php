<?php
require_once('../connection.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $_POST['book_id'];
    $user_id = $_POST['user_id'];
    $returndate = $_POST['date'];

    $inputDate = DateTime::createFromFormat('d/m/Y', $returndate);

    if ($inputDate) {
        $returndateFormatted = $inputDate->format('Y-m-d');
    }

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = :username");
    $stmt->bindParam(':username', $user_id, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($user_id !== null) {
        $updateBorrowStmt = $conn->prepare("UPDATE borrow SET returnstatus = 1 , return_date = :return_date WHERE user_id = :user_id AND book_id = :book_id");

        $updateBorrowStmt->bindParam(':return_date', $returndateFormatted, PDO::PARAM_STR);
        $updateBorrowStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $updateBorrowStmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);

        $updateBorrowStmt->execute();

        // Update borrowstatus in books table
        $updateBooksStmt = $conn->prepare("UPDATE books SET borrowstatus = borrowstatus - 1 WHERE book_id = :book_id");
        $updateBooksStmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);
        $updateBooksStmt->execute();

        $stmtBooks = $conn->prepare("SELECT * FROM books WHERE book_id = :book_id");
        $stmtBooks->bindParam(':book_id', $book_id, PDO::PARAM_INT);
        $stmtBooks->execute();
        $bookData = $stmtBooks->fetch(PDO::FETCH_ASSOC);

        $book_name = $bookData['book_name'];

        $borrow_data = $conn->prepare("SELECT * FROM borrow WHERE user_id = :user_id AND book_id = :book_id");
        $borrow_data->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $borrow_data->bindParam(':book_id', $book_id, PDO::PARAM_INT);
        $borrow_data->execute();
        $borrow = $borrow_data->fetch(PDO::FETCH_ASSOC);

        $borrow_date = new DateTime($borrow['borrow_date']);
        $return_date = new DateTime($borrow['return_date']);
        $interval = $return_date->diff($borrow_date);
        $borrow_days = $interval->days;

        if ($borrow_days > 7) {
            $totalfee = $borrow_days * $borrow['feeperday'];
        }

        echo '<script>';
        echo "alert('คืนหนังสือสำเร็จ! $book_name \\nค่าธรรมเนียมยืมหนังสือเกินเวลากำหนด \\nทั้งหมด $totalfee บาท');";
        echo 'window.location.href = "../returning.php";';
        echo '</script>';

    } else {
        echo "User not found.";
    }






}
if (count($_POST) > 0) {
    $_POST = array();
}
?>