<!DOCTYPE html>
<html>


<?php
include("header.php");
require('connection.php');

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
?>


<body
    style="background-color: #2F5597; background-image: url('img/bg.jpg'); background-size: cover; background-position: center; height: 100vh;">
    <div class="bg-primary">
        <div
            class="container d-flex flex-wrap justify-content-center py-3  mx-auto border-bottom text-white bg-primary px-3">
            <a class="d-flex align-items-center  mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <span class="fs-4 text-white m-1 text-shadow">
                    ผู้ดูแลระบบ
                </span></a>
            <div class="rounded d-flex align-items-center mb-md-0 mx-1 link-body-emphasis text-decoration-none">
                <?php
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
    <div class="navbar bg-dark ">
        <div class="container">
            <div class="btn-group btn-group-toggle mx-auto ">
                <div class="col-auto ">
                    <a class="btn btn-warning  rounded-0 px-4 border-dark" href="admin-main.php">แก้ไขบัญชีผู้ใช้</a>
                </div>
                <div class="col-auto">
                    <a class="btn btn-warning  rounded-0 px-4 border-dark  disabled" href="Fee.php">ปรับค่าบทลงโทษ</a>
                </div>

                <div class="col-auto">
                    <a class="btn btn-danger  rounded-0 px-4 border-dark " href="logout.php">ออกจากระบบ</a>
                </div>
            </div>


        </div>
    </div>
    <div class="container">
        <div class=" d-flex justify-content-center align-items-center  ">
            <div class="  rounded-4 overflow-hidden flex-md-row my-4  shadow-sm h-md-250 position-relative"
                style="background-color:#FFFFFF">
                <div class="bg-dark m-0 text-white text-center py-4">
                    <p class="h2 m-0">ค่าธรรมเนียมการยืมหนังสือ</p>
                </div>

                <?php
                $strSQL = "SELECT feeperday FROM borrow ORDER BY borrow_id DESC LIMIT 1";
                $result = $connection->query($strSQL);

                // ALTER TABLE `borrow` CHANGE `feeperday` `feeperday` INT(11) NOT NULL DEFAULT '$fee';
                //update `borrow` SET `feeperday` = '$fee';
                

                if ($result) {
                    // Fetch the last row from the result set
                    $row = $result->fetch_assoc();

                    // Check if there's a row fetched
                    if ($row) {
                        $feeperdayValue = $row['feeperday'];
                    } else {
                        $feeperdayValue = "";
                    }
                } else {
                    echo "เกิดข้อผิดพลาด : " . $connection->error;
                }
                $connection->close();
                ?>
                <div>
                    <form class="p-4 px-5" style="min-width: 450px; width:768px;" name="form1" method="post"
                        action="php/Fee-value.php">
                        <div class="mb-3">
                            <p class="h5 m-1">ค่าปรับต่อวัน
                            </p>
                            <input type="number" class="form-control" name="fee" id="fee"
                                value="<?php echo $feeperdayValue; ?>" required max="1000">

                        </div>

                        <div class=" pt-3">
                            <button type="submit" class="btn btn-lg btn-success form-control">บันทึกค่าปรับ</button>
                            <a href="admin-main.php" class="mt-1 btn btn-lg btn-danger w-100">ยกเลิก</a>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
    <?php
    include 'script.php';
    ?>
</body>

</html>