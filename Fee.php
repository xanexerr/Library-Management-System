<!DOCTYPE html>
<html>


<?php
include("header.php");
require('connection.php');
?>


<body
    style="background-color:#2F5597; background-image: url('img/bg.jpg'); background-position: center; background-size: cover; ">
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
            
            if (isset($_POST['fee'])) {
                $fee = $_POST['fee'];

                // Sanitize the input to prevent SQL injection
                $fee = mysqli_real_escape_string($connection, $fee);

                // Update the column data type and default value based on the posted fee value
                $alterQuery = "ALTER TABLE `borrow` MODIFY `feeperday` INT(11) NOT NULL DEFAULT '$fee'";


                // Update the existing rows in the table with the new fee value
                $updateQuery = "UPDATE `borrow` SET `feeperday` = '$fee'";
                if (($connection->query($updateQuery) === TRUE) && ($connection->query($alterQuery) === TRUE)) {
                    echo '<script>';
                    echo 'alert("แก้ไขค่าปรับต่อวันแล้ว");';
                    echo '</script>';
                } else {
                    echo "Error updating rows: " . $connection->error;
                }
            }

            if ($result) {
                // Fetch the last row from the result set
                $row = $result->fetch_assoc();

                // Check if there's a row fetched
                if ($row) {
                    // Get the 'feeperday' value for the last row
                    $feeperdayValue = $row['feeperday'];
                } else {
                    // If no rows were found, set a default value or handle it accordingly
                    $feeperdayValue = ""; // Or set a default value
                }
            } else {
                // If the query fails, handle the error (e.g., display an error message)
                echo "Error executing the query: " . $connection->error;
            }

            // Don't forget to close the connection after you're done
            $connection->close();
            ?>
            <div>
                <form class="p-4 px-5" style="min-width: 450px; width:768px;" name="form1" method="post"
                    action="Fee.php">
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