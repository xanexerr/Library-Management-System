<!DOCTYPE html>
<html lang="en">

<!-- header  -->
<?php
include("header.php")
    ?>
<!-- body -->

<body
    style="background-color:#2F5597; background-image: url('img/bg.jpg'); background-position: center; background-size: cover; ">
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

    <div class="container  bg-white shadow border p-0 col-8 my-2 rounded ">
        <p class='h4 py-2  bg-dark border text-white  mb-0 text-center  rounded-top'>ระบบเพิ่มสมาชิก</p>
        <div class="">

            <form class="container p-4 align-content-center " action="php/add-member.php" name="addwp" method="POST">

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
                    บันทึก</button>

                <a href="<?php if ($_SESSION['role'] == 'librarian') {
                    echo "librarian-users.php";
                } elseif ($_SESSION['role'] == 'admin') {
                    echo "admin-main.php";
                } ?>" class="mt-1 btn btn-danger w-100">ยกเลิก</a>

            </form>

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