<!DOCTYPE html>
<html lang="en">

<!-- header  -->
<?php
include("header.php")

    ?>
<!-- body -->

<body style="
            background-color: #2F5597;
            background-image: url('img/bg.jpg');
            background-size: 100%;
            ">
    <div class="bg-primary">
        <div
            class="container d-flex flex-wrap justify-content-center py-3  mx-auto border-bottom text-white bg-primary px-3">
            <a class="d-flex align-items-center  mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <span class="fs-4 text-white m-1 text-shadow">
                    ระบบบรรณารักษณ์
                </span></a>
            <div class="rounded d-flex align-items-center mb-md-0 mx-1 link-body-emphasis text-decoration-none">
                <?php
                session_start();
                if (isset($_SESSION['user_fname']) && $_SESSION['user_lname']) {
                    $nowuser_fname = $_SESSION["user_fname"];
                    $nowuser_lname = $_SESSION["user_lname"];
                    echo "<span class='fs-5 bg-warning rounded p-1 px-3' style='font-size: 16px;'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor'
                        class='bi bi-person-circle' viewBox='0 0 16 16'
                        style='width: 1em; height: 1em; vertical-align: -0.125em;'>
                        <path d='M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0'></path>
                        <path fill-rule='evenodd'
                            d='M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1'>
                        </path>
                    </svg>
                    <span style='font-size: 1em;'>ยินดีต้อนรับ : $nowuser_fname $nowuser_lname</span>
                </span>";
                }
                ?>
            </div>

        </div>
    </div>
    <div class="navbar bg-dark">
        <div class="container">
            <div class="btn-group btn-group-toggle mx-auto">
                <div class="col-auto">
                    <a class="btn btn-success  rounded-0 px-4 border-dark " href="librarian_main.php">หน้าแรก</a>
                </div>
                <div class="col-auto">
                    <a class="btn btn-warning  rounded-0 px-4 border-dark"
                        href="librarian-users.php">แก้ไขบัญชีผู้ใช้</a>
                </div>

                <div class="col-auto">
                    <a class="btn btn-warning  rounded-0 px-4 border-dark" href="lb-book.php">หนังสือทั้งหมด</a>
                </div>

                <div class="col-auto">
                    <a class="btn btn-warning  rounded-0 px-4 border-dark disabled" href="borrowing.php">ยืมหนังสือ</a>
                </div>
                <div class="col-auto">
                    <a class="btn btn-warning  rounded-0 px-4 border-dark" href="returning.php">คืนหนังสือ</a>
                </div>
                <div class="col-auto">
                    <a class="btn btn-warning  rounded-0 px-4 border-dark"
                        href="borrowhistory.php">ข้อมูลการยืมหนังสือ</a>
                </div>
                <div class="col-auto">
                    <a href="logout.php" class="btn btn-danger border-dark rounded-0 px-4 ">ออกจากระบบ</a>
                </div>
            </div>
        </div>
    </div>
    <!-- top banner  -->
    <?php
    require('connection.php');
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
    ?>
    <!-- content -->
    <?php
    $stmtType = $conn->prepare("SELECT * FROM book_types");
    $stmtType->execute();
    $typeData = $stmtType->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_GET['book_id']) && !empty($_GET['book_id'])) {
        $book_id = $_GET['book_id'];
        $stmtBookDetails = $conn->prepare("SELECT * FROM books WHERE book_id = :book_id AND borrowstatus < bookvalue");
        $stmtBookDetails->bindParam(':book_id', $book_id, PDO::PARAM_INT);
        $stmtBookDetails->execute();
        $bookDetails = $stmtBookDetails->fetch(PDO::FETCH_ASSOC);
    } else {
        $stmtBooks = $conn->prepare("SELECT * FROM books WHERE borrowstatus < bookvalue");
        $stmtBooks->execute();
        $bookData = $stmtBooks->fetchAll(PDO::FETCH_ASSOC);
    }

    ?>

    <!-- Use $typeData and $bookData (or $bookDetails) in your HTML -->

    <div class="container  bg-white shadow  p-0 col-8 my-3 ">
        <p class='h4 py-2  bg-dark text-white  mb-0 text-center '>ระบบยืมหนังสือ</p>
        <div class="">
            <form class="container align-content-center p-4 pb-0" method='GET' id="booksearch">
                <div class="form-group">
                    <label for="user_id">รหัสหนังสือ</label>
                    <input class="form-control" type="text" name="book_id" id="book_id" value="<?php if (isset($book_id)) {
                        echo $book_id;
                    } ?>">
                </div>
            </form>

            <form class="container p-4 pt-0 align-content-center " action="php/borrowing.php" name="addwp"
                method="POST">
                <div class="form-group">
                    <label for="book_selector">เลือกหนังสือ </label>
                    <select name="book_id" id="book_selector" class="form-control select2" required>
                        <?php foreach ($bookData as $book): ?>
                            <?php if (isset($book['book_id']) && isset($book['book_name'])): ?>
                                <option value="<?php echo $book['book_id']; ?>">
                                    <?php echo $book['book_id'], " ", $book['book_name']; ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <?php if ($bookDetails) {
                            if (isset($_GET['book_id']) && !empty($_GET['book_id'])) {
                                $book_id = $_GET['book_id']; ?>
                                <option value="<?php echo $book_id; ?>" selected>
                                    <?php echo $bookDetails['book_name']; ?>
                                </option>
                            <?php } else {
                                echo '<script>';
                                echo 'alert("ไม่พบหนังสือ");';
                                echo '</script>';
                            }
                        } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="user_id">รหัสสมาชิก</label>
                    <input class="form-control" type="text" name="user_id" id="user_id" required>
                </div>

                <div class="form-group">
                    <label for="date">วันที่ยืม (วัน/เดือน/ปี)</label>
                    <input type="text" class="form-control" name="date" id="date" value="<?php echo date('d/m/Y'); ?>"
                        required>
                </div>

                <button class="btn btn-success form-control mt-3" type="submit" name="submit" value="Submit">
                    บันทึก</button>
                <a href="librarian_main.php" class="mt-1 btn btn-danger w-100">ยกเลิก</a>
            </form>

        </div>
        <?php
        include 'script.php';
        ?>

</body>

</html>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#date", {
            dateFormat: "d/m/Y",
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('booksearch');
        var inputField = document.getElementById('book_id');

        inputField.addEventListener('input', function () {
            // Delay for 3 seconds
            setTimeout(function () {
                form.submit();
            }, 1000);
        });
    });
</script>