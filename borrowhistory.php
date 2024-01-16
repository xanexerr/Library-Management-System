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
                    <a class="btn btn-success  rounded-0 px-4 border-dark " href="librarian_main.php">หน้าแรก</a>
                </div>
                <div class="col-auto">
                    <a class="btn btn-warning  rounded-0 px-4 border-dark "
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
                    <a class="btn btn-warning  rounded-0 px-4 border-dark disabled"
                        href="borrowhistory.php">ข้อมูลการยืมหนังสือ</a>
                </div>
                <div class="col-auto">
                    <a href="logout.php" class="btn btn-danger border-dark rounded-0 px-4 ">ออกจากระบบ</a>
                </div>
            </div>
        </div>
    </div>


    <?php
    $updatestatus = "SELECT COUNT(*) FROM borrow";
    $stmt = $conn->query($updatestatus);
    $totalusers = $stmt->fetchColumn();
    $borrowData = $conn->query("SELECT * FROM borrow")->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <?php

    $limit = 25;
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($currentPage - 1) * $limit;

    $stmt = $conn->prepare("SELECT COUNT(*) FROM borrow");
    $stmt->execute();
    $totalRows = $stmt->fetchColumn();

    $stmt = $conn->prepare("SELECT * FROM borrow  ORDER BY borrow_id DESC LIMIT :limit OFFSET :offset");

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $borrowData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalPages = ceil($totalRows / $limit);


    ?>
    <div class="flex-container">
        <div class="container ">
            <div class="my-3 bg-body  shadow justify-content-center   ">
                <div>
                    <p class='fs-4 p-2 text-center bg-dark text-white m-0 '>
                        ข้อมูลผู้ใช้ทั้งหมด </p>

                    <div class="container text-center bg-warning">
                        <div class="btn-group  btn-group-toggle mx-auto">
                            <div class="col-auto">
                                <a class="btn btn-warning  rounded-0 px-4 border-dark"
                                    href="add-member.php">สมาชิกใหม่</a>
                            </div>
                        </div>
                    </div>

                    <div class="px-4">
                        <div class="">
                            <p class="fs-5 rounded p-1 px-3 m-0 form-control border-0 text-center">
                                ข้อมูลที่ค้นพบ :
                                <?php echo $totalRows; ?>
                            </p>
                        </div>
                    </div>

                    <div class="col mx-3">
                        <div class="table-responsive">
                            <?php if ($totalusers > 0): ?>
                                <table class="table table-bordered table-sm m-0">
                                    <thead>
                                        <tr class="text-center text-light bg-dark col-12">
                                            <th class='col-2'>ผู้ยืม</th>
                                            <th class='col-2'>ชื่อผู้ยืม</th>
                                            <th class='col-1'>รหัสหนังสือ</th>
                                            <th class='col-2'>ชื่อหนังสือ</th>
                                            <th class='col-3'>วันที่ยืม/วันที่คืน</th>
                                            <th class='col-1'>ระยะเวลา</th>
                                            <th class='col-2'>สถานะ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($borrowData as $row): ?>
                                            <tr class="text-center ">
                                                <td>
                                                    <?php
                                                    $user_id = $row['user_id'];
                                                    $stmt = $conn->prepare("SELECT username FROM users WHERE user_id = :user_id");
                                                    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                                                    $stmt->execute();
                                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                                    $borrowusername = $result['username'];
                                                    echo $borrowusername; ?>
                                                </td>
                                                <td class='text-center'>
                                                    <?php
                                                    $user_id = $row['user_id'];
                                                    $stmt = $conn->prepare("SELECT user_fname, user_lname FROM users WHERE user_id = :user_id");
                                                    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                                                    $stmt->execute();

                                                    // Check if the query was successful and returned a result
                                                    if ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                        $fname = $result['user_fname'];
                                                        $lname = $result['user_lname'];
                                                        echo "$fname $lname";
                                                    } else {
                                                        echo "";
                                                    }
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php echo $row['book_id']; ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    $book_id = $row['book_id'];
                                                    $stmtBookDetails = $conn->prepare("SELECT * FROM books WHERE book_id = :book_id");
                                                    $stmtBookDetails->bindParam(':book_id', $book_id, PDO::PARAM_INT);
                                                    $stmtBookDetails->execute();
                                                    $bookDetails = $stmtBookDetails->fetch(PDO::FETCH_ASSOC);

                                                    echo $bookDetails['book_name'];
                                                    ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    $borrow_date = $row['borrow_date'];
                                                    $formattedBorrowDate = DateTime::createFromFormat('Y-m-d', $borrow_date)->format('d M Y');
                                                    echo $formattedBorrowDate;


                                                    if (!empty($row['return_date'])) {
                                                        echo " ถึง";
                                                        $return_date = $row['return_date'];
                                                        $formattedReturnDate = DateTime::createFromFormat('Y-m-d', $return_date)->format('d M Y');
                                                        echo $formattedReturnDate;
                                                    }

                                                    ?>
                                                </td>



                                                <td>
                                                    <?php
                                                    if (!empty($row['return_date'])) {
                                                        $borrow_date = new DateTime($row['borrow_date']);
                                                        $return_date = new DateTime($row['return_date']);
                                                        $interval = $return_date->diff($borrow_date);
                                                        $borrow_days = $interval->days;
                                                        echo $borrow_days . " วัน";
                                                    } ?>
                                                </td>

                                                <?php
                                                if ($row['returnstatus'] == 1) { ?>
                                                    <td class=" text-center bg-success text-white">
                                                        คืนแล้ว
                                                    </td>
                                                <?php } else { ?>
                                                    <td class="text-center bg-danger text-white">
                                                        ยังไม่คืน
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                            <div class="modal fade" id="my-modal<?php echo $row['workplace_id']; ?>"
                                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            </div>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p class='mt-5'>No data available</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="container p-3 ">
                        <nav aria-label=" Page navigation example  ">
                            <ul class="pagination justify-content-center m-0">
                                <?php if ($currentPage > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link bg-dark text-white"
                                            href="?page=<?php echo ($currentPage - 1); ?>" aria-label="Previous">
                                            <span aria-hidden="true">
                                                &#60;
                                            </span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                                        <a class="page-link  " href="?page=<?php echo $i; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php endfor; ?>
                                <?php if ($currentPage < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link bg-dark text-white"
                                            href="?page=<?php echo ($currentPage + 1); ?>" aria-label="Next">
                                            <span aria-hidden="true"> &#62;</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                </div>

            </div>
        </div>





        <?php include('script.php'); ?>
</body>

</html>