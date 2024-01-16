<!DOCTYPE html>
<html lang="en">

<!-- header  -->
<?php
session_start();
include("header.php")
    ?>
<!-- body -->

<body
    style="background-color:#2F5597; background-image: url('img/bg.jpg'); background-position: center; background-size: cover; ">
    <?php if ($_SESSION["role"] == 'librarian') { ?>
        <div class="bg-primary">
            <div
                class="container d-flex flex-wrap justify-content-center py-3  mx-auto border-bottom text-white bg-primary px-3">
                <a class="d-flex align-items-center  mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                    <span class="fs-4 text-white m-1 text-shadow">
                        ระบบบรรณารักษณ์
                    </span></a>
                <div class="rounded d-flex align-items-center mb-md-0 mx-1 link-body-emphasis text-decoration-none">
                    <?php

                    require('connection.php');
                    if (!isset($_SESSION["username"])) {
                        echo '<script>';
                        echo 'alert("คุณยังไม่ได้เข้าสู่ระบบ");';
                        echo 'window.location.href = "login.php";';
                        echo '</script>';
                        exit();
                    } else if ($_SESSION["role"] !== 'librarian' && $_SESSION["role"] !== 'admin') {
                        echo '<script>';
                        echo 'alert("คุณไม่มีสิทธิเข้าถึง!");';
                        echo 'window.location.href = "index.php";';
                        echo '</script>';
                        exit();
                    }

                    if (isset($_SESSION['user_fname']) && $_SESSION['user_lname']) {
                        $nowuser_fname = $_SESSION["user_fname"];
                        $nowuser_lname = $_SESSION["user_lname"];
                        echo "
                    <span class='fs-5 bg-warning rounded p-1 px-3' style='font-size: 16px;'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor'
                        class='bi bi-person-circle' viewBox='0 0 16 16'
                        style='width: 1em; height: 1em; vertical-align: -0.125em;'>
                        <path d='M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0'></path>
                        <path fill-rule='evenodd'
                            d='M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1'>
                        </path>
                    </svg>
                    <span style='font-size: 1em;'> ยินดีต้อนรับ : $nowuser_fname $nowuser_lname</span>
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
                        <a class="btn btn-warning  rounded-0 px-4 border-dark disabled"
                            href="lb-book.php">หนังสือทั้งหมด</a>
                    </div>

                    <div class="col-auto">
                        <a class="btn btn-warning  rounded-0 px-4 border-dark" href="borrowing.php">ยืมหนังสือ</a>
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
    <?php } ?>
    <?php
    require('connection.php');
    if (!isset($_SESSION["username"])) {
        echo '<script>';
        echo 'alert("คุณยังไม่ได้เข้าสู่ระบบ");';
        echo 'window.location.href = "login.php";';
        echo '</script>';
        exit();
    } else if ($_SESSION["role"] !== 'librarian' && $_SESSION["role"] !== 'admin') {
        echo '<script>';
        echo 'alert("คุณไม่มีสิทธิเข้าถึง!");';
        echo 'window.location.href = "index.php";';
        echo '</script>';
        exit();
    }

    $stmt = $conn->prepare("SELECT COUNT(*) FROM book_types ");
    $stmt->execute();
    $totalRows = $stmt->fetchColumn();

    $stmt = $conn->prepare("SELECT * FROM book_types ");
    $stmt->execute();
    $typeData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>
    <!-- content -->

    <div class="container">

        <div class="d-flex justify-content-center align-items-center vh-100 ">
            <div
                class="g-0 rounded-4 overflow-hidden flex-md-row my-4  shadow-sm w-50 h-md-250 position-relative bg-white">
                <p class='h4 py-2  bg-dark text-white  mb-0 text-center '>ระบบเพิ่มสมาชิก</p>
                <form class="container p-4 align-content-center " action="php/add-member.php" name="addwp"
                    method="POST">

                    <div class="form-group">
                        <label for="user_fname">ชื่อจริง</label>
                        <input class="form-control" type="text" name="user_fname" id="user_fname" required>
                    </div>

                    <div class="form-group">
                        <label for="user_lname">นามสกุล</label>
                        <input class="form-control" type="text" name="user_lname" id="user_lname" required>
                    </div>

                    <div class="form-group">
                        <label for="username">รหัสประจำตัว</label>
                        <input class="form-control" type="text" name="username" id="username" required>
                    </div>

                    <div class="form-group">
                        <label for="user_type">ประเภทบัญชี</label>
                        <select class="form-control" name="user_type" id="user_type">

                            <?php if ($_SESSION['role'] == 'librarian') { ?>
                                <option value="student">นักเรียน</option>
                            <?php } ?>
                            <?php if ($_SESSION['role'] == 'admin') { ?>
                                <option value="student">นักเรียน</option>
                                <option value="librarian">บรรณารักษณ์</option>
                            <?php } ?>
                        </select>
                    </div>

                    <button class="btn btn-success form-control mt-3" type="submit" name="submit" value="Submit">
                        เพิ่ม</button>

                    <a href="<?php if ($_SESSION['role'] == 'librarian') {
                        echo "librarian-users.php";
                    } elseif ($_SESSION['role'] == 'admin') {
                        echo "admin-main.php";
                    } ?>" class="mt-1 btn btn-danger w-100">ยกเลิก</a>

                </form>
            </div>
        </div>
    </div>
    <?php
    include 'script.php';
    ?>

</body>

</html>


<script>
    // เลือก input element โดยใช้ ID
    const bookValueInput = document.getElementById('bookvalue');


    bookValueInput.addEventListener('change', function () {

        if (this.value < 1) {
            alert('ใส่จำนวนหนังสือให้ถูกต้อง');
            this.value = 1;
        }
    });
</script>