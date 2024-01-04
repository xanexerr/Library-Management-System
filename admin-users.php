<!DOCTYPE html>
<html lang="en">

<!-- header  -->
<?php
include("header.php")
    ?>
<!-- body -->

<body>
    <!-- top banner  -->
    <?php
    session_start();
    require('connection.php');
    if (!isset($_SESSION["username"])) {
        echo '<script>';
        echo 'alert("คุณยังไม่ได้เข้าสู่ระบบ");';
        echo 'window.location.href = "login.php";';
        echo '</script>';
        exit();
    } else {
        if ($_SESSION["role"] !== 'admin') {
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
        <div class="container  px-0 border   shadow rounded my-3 col-md-10">
            <p class='h4 py-2  bg-dark border text-white  mb-0 text-center  rounded-top '>รายละเอียด </p>
            <div class="">
                <div class="">
                    <?php foreach ($userData as $row): ?>

                        <form class="border p-3" name="edit_workplace_form" method="POST" action="php/admin-wp-update.php"
                            enctype="multipart/form-data">

                            <label for="workplace_id" class="form-label">รหัสผู้ใช้</label>
                            <input type="text" class="form-control text-danger" name="workplace_id" readonly
                                value="<?php echo $row['user_id']; ?>">


                            <label for="workplace_name" class="form-label  mt-1">ชื่อบัญชี</label>
                            <input type="text" class="form-control" name="workplace_name"
                                value="<?php echo $row['username']; ?>" required>

                            <label for="user_fname" class="form-label mt-1">นามสกุล</label>
                            <input type="text" class="form-control" name="user_fname"
                                value="<?php echo $row['user_fname']; ?>" required>

                            <label for="user_lname" class="form-label mt-1">นามสกุล</label>
                            <input type="text" class="form-control" name="user_lname"
                                value="<?php echo $row['user_lname']; ?>" required>

                            <label for="role" class="form-label mt-1">สถานะ</label>
                            <select class="form-control" name="role" required>
                                <option value="admin" <?php if ($row['role'] == 'admin') {
                                    echo 'selected';
                                } ?>>Admin</option>
                                <option value="librarian" <?php if ($row['role'] == 'librarian') {
                                    echo 'selected';
                                } ?>>Librarian</option>
                            </select>


                            <button type="submit" value="submit"
                                class="mt-3 btn btn-success w-100">บันทึกการเปลี่ยนแปลง</button>
                            <a href="admin-wp.php" class="mt-1 btn btn-danger w-100">ยกเลิก</a>
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

</body>

</html>