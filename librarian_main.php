<!DOCTYPE html>
<html lang="en">
<?php include('header.php'); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sidebars/">
    <title>Document</title>
</head>

<body style="background-color:#2F5597; 
    background-image: url('img/bg.jpg'); 
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
    <?php
    require('connection.php');
    if (!isset($_SESSION["username"])) {
        echo '<script>';
        echo 'alert("คุณยังไม่ได้เข้าสู่ระบบ");';
        echo 'window.location.href = "login.php";';
        echo '</script>';
        exit();
    }
    ?>
    <div class="navbar bg-dark">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-auto">
                    <a class="btn btn-warning" href="librarian-users.php">แก้ไขบัญชีผู้ใช้</a>
                </div>

                <div class="col-auto">
                    <a class="btn btn-warning" href="#">หนังสือทั้งหมด</a>
                </div>
                <div class="col-auto">
                    <a class="btn btn-warning" href="#">เพิ่มหนังสือ</a>
                </div>
                <div class="col-auto">
                    <a class="btn btn-warning" href="#">ยืมหนังสือ</a>
                </div>
                <div class="col-auto">
                    <a class="btn btn-warning" href="#">คืนหนังสือ</a>
                </div>
                <div class="col-auto">
                    <a class="btn btn-warning" href="#">ข้อมูลการยืมหนังสือ</a>
                </div>
                <div class="col-auto">
                    <a href="logout.php" class="btn btn-danger">ออกจากระบบ</a>
                </div>
            </div>
        </div>
    </div>


    <div class="flex-container">
        <div class="container ">
            <div class="my-3 bg-body  shadow">
                <div class=" justify-content-center ">

                </div>
            </div>
        </div>
    </div>
    </div>



    <?php include('script.php'); ?>
</body>

</html>