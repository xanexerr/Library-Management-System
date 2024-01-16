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


<body style="background-color:#2F5597; background-image: url('img/bg.jpg'); background-position: center; weight:100vh;">
    <div class=" container d-flex justify-content-center align-items-center  p-5">
        <div class="g-0 border rounded overflow-hidden flex-md-row my-4  shadow-sm h-md-250 position-relative"
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
                            value="<?php echo $feeperdayValue; ?>" required>
                    </div>

                    <div class=" pt-3">
                        <button type="submit" class="btn btn-lg btn-success form-control">ปรับค่าปรับ</button>
                        <a href="admin-main.php" class="mt-1 btn btn-lg btn-danger w-100">ยกเลิก</a>
                    </div>
                </form>


            </div>
        </div>
    </div>
    <?php
    include 'script.php';
    ?>
</body>

</html>