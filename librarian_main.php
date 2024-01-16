<!DOCTYPE html>
<html lang="en">
<?php include('header.php'); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sidebars/">
    <title>Document</title>
</head>

<body style="
            background-color: #2F5597;
            background-image: url('img/bg.jpg');
            background-size: 100%;
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
    } else {
        if ($_SESSION["role"] !== 'librarian') {
            echo '<script>';
            echo 'alert("คุณไม่มีสิทธิเข้าถึง!");';
            echo 'window.location.href = "index.php";';
            echo '</script>';
            exit();
        }
    }
    ?>
    <div class="navbar bg-dark">
        <div class="container">
            <div class="btn-group btn-group-toggle mx-auto">
                <div class="col-auto">
                    <a class="btn btn-success  rounded-0 px-4 border-dark disabled"
                        href="librarian_main.php">หน้าแรก</a>
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
    $totalBooksQuery = "SELECT COUNT(book_id) AS total_books FROM `books`";
    $totalBooksResult = $conn->query($totalBooksQuery);
    $totalBooks = $totalBooksResult->fetch(PDO::FETCH_ASSOC)['total_books'];

    // Query to get total number of students
    $totalStudentsQuery = "SELECT COUNT(user_id) AS total_students FROM users WHERE role = 'student'";
    $totalStudentsResult = $conn->query($totalStudentsQuery);
    $totalStudents = $totalStudentsResult->fetch(PDO::FETCH_ASSOC)['total_students'];

    // Query to get number of books not returned
    $notReturnedQuery = "SELECT COUNT(user_id) AS not_returned_books FROM `borrow` WHERE returnstatus = '0'";
    $notReturnedResult = $conn->query($notReturnedQuery);
    $notReturned = $notReturnedResult->fetch(PDO::FETCH_ASSOC)['not_returned_books'];

    // Query to calculate overdue fees
    $FeeQuery = "SELECT feeperday FROM `borrow` ORDER BY borrow_id DESC LIMIT 1";
    $FeeResult = $conn->query($FeeQuery);
    if ($FeeResult) {
        $Fee = $FeeResult->fetch(PDO::FETCH_ASSOC)['feeperday'];
        // Now $Fee contains the 'feeperday' value from the first row based on the descending order
    } else {
        // Handle error if query fails
        echo "Error executing query";
    }


    // Display or use the retrieved data as needed
    

    ?>
    <div class="flex-container ">
        <div class="container  ">
            <div class="my-3   bg-none ">
                <div class=" justify-content-center ">

                    <div class="container p-4 col-10 ">
                        <div class="row ">
                            <div class="col-md-6 mb-4">
                                <div class="card text-white bg-primary">
                                    <div class="card-body my-4 ">
                                        <h4 class="card-title text-center">จำนวนหนังสือทั้งหมด</h4>
                                        <p class="card-text fs-1 text-center"><strong>
                                                <?php echo $totalBooks; ?> เล่ม
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card text-white bg-success">
                                    <div class="card-body my-4">
                                        <h4 class="card-title text-center">จำนวนสมาชิกทั้งหมด</h4>
                                        <p class="card-text fs-1 text-center"><strong>
                                                <?php echo $totalStudents; ?> คน
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4 ">
                                <div class="card text-white bg-danger">
                                    <div class="card-body my-4">
                                        <h4 class="card-title text-center">จำนวนการยืมหนังสือทั้งหมด</h4>
                                        <p class="card-text fs-1 text-center"><strong>
                                                <?php echo $notReturned; ?> เล่ม
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4 ">
                                <div class="card text-white bg-secondary">
                                    <div class="card-body my-4">
                                        <h4 class="card-title text-center">ค่าธรรมเนียมคืนหนังสือเลยกำหนด</h4>
                                        <p class="card-text fs-1 text-center"><strong>
                                                <?php echo $Fee; ?> บาท/วัน
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>



            <?php include('script.php'); ?>
</body>

</html>