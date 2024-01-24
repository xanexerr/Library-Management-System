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
    if (isset($_POST['book_id'], $_POST['user_id'], $_POST['date'])) {
        $book_id = $_POST['book_id'];
        $username = $_POST['user_id'];
        $returndate = $_POST['date'];

        $inputDate = DateTime::createFromFormat('d/m/Y', $returndate);
        if (empty($book_id)) {
            echo '<script>';
            echo "Swal.fire({
            title: 'ไม่หนังสือ',
            icon: 'error',
            confirmButtonText: 'ตกลง '
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../returning.php';
            }
        });";
            echo '</script>';
            exit();
        }
        if ($inputDate) {
            $returndateFormatted = $inputDate->format('Y-m-d');

            $checkUserStmt = $conn->prepare("SELECT user_id FROM users WHERE user_id = :username");
            $checkUserStmt->bindParam(':username', $username, PDO::PARAM_STR);
            $checkUserStmt->execute();
            $userExists = $checkUserStmt->fetch(PDO::FETCH_ASSOC);

            if (empty($userExists)) {
                echo '<script>';
                echo "Swal.fire({
            title: 'ไม่พบผู้ใช้',
            icon: 'error',
            confirmButtonText: 'ตกลง '
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../returning.php';
            }
        });";
                echo '</script>';
                exit();
            }

            $user_id = $userExists['user_id'];

            $updateBorrowStmt = $conn->prepare("UPDATE borrow SET returnstatus = 1, return_date = :return_date WHERE user_id = :user_id AND book_id = :book_id");
            $updateBorrowStmt->bindParam(':return_date', $returndateFormatted, PDO::PARAM_STR);
            $updateBorrowStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $updateBorrowStmt->bindParam(':book_id', $book_id, PDO::PARAM_INT);

            try {
                $updateBorrowStmt->execute();
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

                $totalfee = ($borrow_days > 7) ? $borrow_days * $borrow['feeperday'] : 0;
                ?>
                <script>
                    Swal.fire({
                        title: 'คืนหนังสือสำเร็จ!',
                        html: '<?= $book_name ?><br>ค่าธรรมเนียมยืมหนังสือเกินเวลากำหนด<br>ทั้งหมด <?= $totalfee ?> บาท',
                        icon: 'success',
                        confirmButtonText: 'ตกลง'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../returning.php';
                        }
                    });
                </script>
                <?php
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    } else { ?>
        <script>
            Swal.fire({
                title: 'เกิดข้อผิดพลาด!',
                html: 'ไม่พบข้อมูล!',
                icon: 'error',
                confirmButtonText: 'ตกลง'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../returning.php';
                }
            });
        </script>
    <?php }

    include '../script.php';

    ?>
</body>

</html>