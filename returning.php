<!DOCTYPE html>
<html lang="en">

<!-- header  -->
<?php
include("header.php")
    ?>
<!-- body -->

<body
    style="background-color:#2F5597; background-image: url('img/bg.jpg'); background-position: center; background-size:auto; ">
    <!-- top banner  -->
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
                    <span style='font-size: 1em;'>$nowuser_fname $nowuser_lname</span>
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
                    <a class="btn btn-warning  rounded-0 px-4 border-dark" href="borrowing.php">ยืมหนังสือ</a>
                </div>
                <div class="col-auto">
                    <a class="btn btn-warning  rounded-0 px-4 border-dark disabled" href="returning.php">คืนหนังสือ</a>
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

    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = :username");
        $stmt->bindParam(':username', $user_id, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

        if ($result) {
            $user_id = $result['user_id'];

            $stmt = $conn->prepare("SELECT username FROM users WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $borrowusername = $result['username'];

            $stmt = $conn->prepare("SELECT book_id FROM borrow WHERE user_id = :user_id AND returnstatus = 0");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $bookIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
            $bookData = array();
            foreach ($bookIds as $bookId) {
                $stmt = $conn->prepare("SELECT * FROM books WHERE book_id = :book_id");
                $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
                $stmt->execute();
                $book = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($book) {
                    $bookData[] = $book;
                }
            }
        }
    }

    ?>
    <!-- content -->

    <div class="container  bg-white shadow border p-0 col-8 my-2 rounded ">
        <p class='h4 py-2  bg-dark border text-white  mb-0 text-center  rounded-top'>ระบบยืมหนังสือ</p>
        <div class="">

            <form class="container align-content-center p-4 pb-0" method='GET' action id="usersearch">
                <div class="form-group">
                    <label for="user_id">รหัสสมาชิก</label>
                    <input class="form-control" type="text" name="user_id" id="user_id" value="<?php if (isset($borrowusername)) {
                        echo $borrowusername;
                    } else if (isset($_GET['user_id'])) {
                        echo $_GET['user_id'];
                    } ?>" required>
                </div>
            </form>

            <form class=" container p-4 pt-0 align-content-center " action=" php/return.php" name="addwp" method="POST">
                <input type="hidden" class="form-control" name="user_id" id="user_id" value="<?php echo $user_id; ?>"
                    required>

                <div class="form-group">
                    <?php if (!empty($bookData)): ?>
                        <label for="book_name">หนังสือที่คุณยืมไป</label>
                        <select name="book_id" id="book_id" class="form-control" required>
                            <?php foreach ($bookData as $book): ?>
                                <option value="<?php echo $book['book_id']; ?>">
                                    <?php echo $book['book_name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>

                </div>

                <div class="form-group">
                    <label for="date">วันที่คืน (วัน/เดือน/ปี)</label>
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
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                flatpickr("#date", {
                    dateFormat: "d/m/Y",
                });
            });

            document.addEventListener('DOMContentLoaded', function () {
                var form = document.getElementById('usersearch'); // Replace 'usersearch' with the actual ID of your form
                var inputField = document.getElementById('user_id'); // Replace 'user_id' with the actual ID of your input field

                inputField.addEventListener('input', function () {
                    setTimeout(function () {
                        form.submit();
                    }, 1000);
                });
            });
        </script>


</body>

</html>