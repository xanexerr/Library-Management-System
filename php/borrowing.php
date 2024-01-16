<?php
require_once('../connection.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = $user_id = $borrowdate = "";
    $book_id = $_POST['book_id'];
    $user_id = $_POST['user_id'];
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = :username");
    $stmt->bindParam(':username', $user_id, PDO::PARAM_STR);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $result = $stmt->fetch();
    $user_id = $result['user_id'];
    $borrowdate = $_POST['date'];

    // จำนวนหนังสือที่ยังไม่คืน
    $borrowcount = $conn->prepare("SELECT COUNT(*) as count FROM borrow WHERE user_id = :user_id AND returnstatus = '0'");
    $borrowcount->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $borrowcount->execute();
    $borrowcount->setFetchMode(PDO::FETCH_ASSOC);
    $result = $borrowcount->fetch();


    $youborrow = $conn->prepare("SELECT book_id FROM borrow WHERE user_id = :user_id AND returnstatus = '1'");
    $youborrow->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $youborrow->execute();
    $youborrow->setFetchMode(PDO::FETCH_ASSOC);
    $borrowedBooks = $youborrow->fetchAll();

    // Fetch book names based on the borrowed book IDs
    foreach ($borrowedBooks as $borrowedBook) {
        $book_id = $borrowedBook['book_id'];

        $bookgotborrow = $conn->prepare("SELECT book_name FROM books WHERE book_id = :book_id");
        $bookgotborrow->bindParam(':book_id', $book_id, PDO::PARAM_INT);
        $bookgotborrow->execute();
        $bookgotborrow->setFetchMode(PDO::FETCH_ASSOC);
        $bookData = $bookgotborrow->fetch();
    }


    if ($result['count'] < 3) {
        $insertBorrow = $conn->prepare("INSERT INTO borrow (book_id, user_id, borrow_date) VALUES (:book_id, :user_id, :borrow_date)");
        $borrow_date = date('Y-m-d');
        $insertBorrow->bindParam(':book_id', $book_id, PDO::PARAM_INT);
        $insertBorrow->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $insertBorrow->bindParam(':borrow_date', $borrow_date); // Assuming $borrow_date is in the correct format
        $insertBorrow->execute();

        $updateBorrowStatus = $conn->prepare("UPDATE books SET borrowstatus = borrowstatus + 1 WHERE book_id = :book_id");
        $updateBorrowStatus->bindParam(':book_id', $book_id, PDO::PARAM_INT);
        $updateBorrowStatus->execute();

        $bookgotborrow = $conn->prepare("SELECT book_name FROM books WHERE book_id = :book_id");
        $bookgotborrow->bindParam(':book_id', $book_id, PDO::PARAM_INT);
        $bookgotborrow->execute();
        $bookgotborrow->setFetchMode(PDO::FETCH_ASSOC);
        $bookData = $bookgotborrow->fetch();

        if ($bookData) {
            $book_name = $bookData['book_name'];
            echo '<script>';
            echo "alert('ยืมหนังสือสำเร็จ! $book_name ');";
            echo 'window.location.href = "../borrowing.php";';
            echo '</script>';
        }
    } else {
        echo '<script>';
        echo 'alert("คุณยืมหนังสือครบจำนวนที่กำหนดแล้ว!");';
        echo 'window.location.href = "../borrowing.php";';
        echo '</script>';
    }


}
if (count($_POST) > 0) {
    $_POST = array();
}
?>