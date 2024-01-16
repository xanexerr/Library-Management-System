<!DOCTYPE html>
<html lang="en">

<!-- header  -->
<?php
include("header.php")
    ?>
<!-- body -->

<body style="background-color:#2F5597; background-image: url('img/bg.jpg'); background-size: 100%; ">
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
                    <a class="btn btn-warning  rounded-0 px-4 border-dark disabled"
                        href="librarian-users.php">แก้ไขบัญชีผู้ใช้</a>
                </div>

                <div class="col-auto">
                    <a class="btn btn-warning  rounded-0 px-4 border-dark" href="lb-book.php">หนังสือทั้งหมด</a>
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

    $user_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users ");
    $stmt->execute();
    $totalRows = $stmt->fetchColumn();

    $stmt = $conn->prepare("SELECT * FROM users WHERE `user_id` =  '$user_id' ");
    $stmt->execute();
    $userData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>
    <!-- content -->

    <div class="flex-container">
        <div class="container  px-0   shadow my-3 col-md-10 bg-white w-50">
            <p class='h4 py-2  bg-dark  text-white  mb-0 text-center  '>รายละเอียด </p>
            <div class="">
                <div class="">
                    <?php foreach ($userData as $row): ?>

                        <form class="p-4 " name="edit_workplace_form" method="POST" action="php/update-users.php"
                            enctype="multipart/form-data">

                            <label for="workplace_id" class="form-label">รหัสผู้ใช้</label>
                            <input type="text" class="form-control text-danger" name="user_id" readonly
                                value="<?php echo $row['user_id']; ?>">


                            <label for="username" class="form-label  mt-1">ชื่อบัญชี</label>
                            <input type="text" class="form-control" name="username" value="<?php echo $row['username']; ?>"
                                required>

                            <label for="user_fname" class="form-label mt-1">นามสกุล</label>
                            <input type="text" class="form-control" name="user_fname"
                                value="<?php echo $row['user_fname']; ?>" required>

                            <label for="user_lname" class="form-label mt-1">นามสกุล</label>
                            <input type="text" class="form-control" name="user_lname"
                                value="<?php echo $row['user_lname']; ?>" required>
                            <label for="role" class="form-label mt-1">สถานะ</label>
                            <select class="form-control" name="role" required>
                                <option value="student" <?php if ($row['role'] == 'student') {
                                    echo 'selected';
                                } ?>>นักเรียน</option>
                            </select>

                            <button type="submit" value="submit"
                                class="mt-3 btn btn-success w-100">บันทึกการเปลี่ยนแปลง</button>
                            <a href="#" onclick="confirmDelete(<?php echo $row['user_id']; ?>)"
                                class="mt-1 btn btn-danger w-100">ลบ</a>
                            <a href="librarian-users.php" class="mt-1 btn btn-warning w-100">ยกเลิก</a>
                        </form>

                    <?php endforeach; ?>


                </div>
            </div>
        </div>
    </div>
    </div>
    <?php
    include 'script.php';
    ?>
    <script>
        function confirmDelete(userId) {
            Swal.fire({
                title: 'คุณต้องการลบผู้ใช้นี้หรือไม่?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ใช่, ลบ!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'php/del-member.php?id=' + userId;
                } else {

                }
            });
        }
    </script>
</body>

</html>