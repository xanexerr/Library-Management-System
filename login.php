<!DOCTYPE html>
<html>


<?php
include("header.php");
require('connection.php');
?>


<body style="background-color:#2F5597">
    <div class=" container d-flex justify-content-center align-items-center  p-5">
        <div class="g-0 border rounded overflow-hidden flex-md-row my-4  shadow-sm h-md-250 position-relative"
            style="background-color:#FFFFFF">
            <div class="bg-dark m-0 text-white text-center py-4">
                <p class="h2 m-0">โปรแกรมยืมหนังสือ</p>
            </div>


            <div>
                <form class="p-4 px-5" style="min-width: 450px; width:768px;" name="form1" method="post"
                    action="check_login.php">
                    <div class="mb-3">
                        <p class="h5 m-1">ชื่อบัญชีผู้ใช้</p>
                        <input type="text" class="form-control" name="username" id="username" required>
                    </div>
                    <div class="mb-3">
                        <p class="h5 m-1">รหัสผ่าน</p>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>

                    <div class=" pt-3">
                        <button type="submit" class="btn btn-lg btn-success form-control">เข้าสู่ระบบ</button>
                    </div>
                    <div class="d-grid gap-1 pt-1">
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