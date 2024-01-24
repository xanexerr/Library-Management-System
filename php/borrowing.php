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
    require_once('../connection.php');
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $book_id = $_POST['book_id'];
        $username = $_POST['user_id'];
        $borrowdate = $_POST['date'];
        $inputDate = DateTime::createFromFormat('d/m/Y', $borrowdate);

        if (!$inputDate) {
            echo "Invalid date format!";
            exit();
        }
        $borrowdateFormatted = $inputDate->format('Y-m-d');
        $checkUserStmt = $conn->prepare("SELECT user_id FROM users WHERE username = :username");
        $checkUserStmt->bindParam(':username', $username, PDO::PARAM_STR);
        $checkUserStmt->execute();
        $userExists = $checkUserStmt->fetch(PDO::FETCH_ASSOC);

        if (empty($userExists['user_id'])) {
            echo '<script>';
            echo "Swal.fire({
            title: 'ตรวจไม่พบชื่อผู้ใช้',
            icon: 'error',
            confirmButtonText: 'ตกลง'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../borrowing.php';
            }
        });";
            echo '</script>';
            exit();
        } else {
            $borrowcount = $conn->prepare("SELECT COUNT(*) as count FROM borrow WHERE user_id = :user_id AND returnstatus = '0'");
            $borrowcount->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $borrowcount->execute();
            $borrowCountResult = $borrowcount->fetch(PDO::FETCH_ASSOC);

            if ($borrowCountResult['count'] < 3) {
                $user_id = $userExists['user_id'];
                $insertBorrow = $conn->prepare("INSERT INTO borrow (book_id, user_id, borrow_date) VALUES (:book_id, :user_id, :borrow_date)");
                $insertBorrow->bindParam(':book_id', $book_id, PDO::PARAM_INT);
                $insertBorrow->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $insertBorrow->bindParam(':borrow_date', $borrowdateFormatted);
                $insertBorrow->execute();


                $updateBorrowStatus = $conn->prepare("UPDATE books SET borrowstatus = borrowstatus + 1 WHERE book_id = :book_id");
                $updateBorrowStatus->bindParam(':book_id', $book_id, PDO::PARAM_INT);
                $updateBorrowStatus->execute();
                $bookgotborrow = $conn->prepare("SELECT book_name FROM books WHERE book_id = :book_id");
                $bookgotborrow->bindParam(':book_id', $book_id, PDO::PARAM_INT);
                $bookgotborrow->execute();
                $bookData = $bookgotborrow->fetch(PDO::FETCH_ASSOC);

                if ($bookData) {
                    $book_name = $bookData['book_name'];
                    echo '<script>';
                    echo "Swal.fire({
                            title: 'ยืมหนังสือสำเร็จ!',
                            text: 'ยืมหนังสือสำเร็จ! $book_name',
                            icon: 'success',
                            confirmButtonText: 'ตกลง'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '../borrowing.php';
                            }
                        });";
                    echo '</script>';
                }
            } else {
                echo '<script>';
                echo "Swal.fire({
                        title: 'คุณยืมหนังสือครบจำนวนที่กำหนดแล้ว!',
                        icon: 'error',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../borrowing.php';
                        }
                    });";
                echo '</script>';
            }
        }
        $_POST = array();
    }
    include '../script.php';
    ?>
</body>

</html>