<!DOCTYPE html>
<html lang="en">
<?php include('header.php'); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sidebars/">
    <title>Document</title>
</head>

<body>
    <div class="bg-primary">
        <div
            class="container d-flex flex-wrap justify-content-center py-3  mx-auto border-bottom text-white bg-primary px-3">
            <a class="d-flex align-items-center  mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <span class="fs-4 text-white m-1 text-shadow">
                    ผู้ดูแลระบบ
                </span></a>
            <div class="rounded d-flex align-items-center mb-md-0 mx-1 link-body-emphasis text-decoration-none">
                <?php
                session_start();
                if (isset($_SESSION['nowuser_fname']) && $_SESSION['nowuser_lname']) {
                    $nowuser_fname = $_SESSION["nowuser_fname"];
                    $nowuser_lname = $_SESSION["nowuser_lname"];
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
    <?php
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
    <div class="navbar bg-dark">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-auto">
                    <a class="btn btn-warning disabled" href="admin-main.php">แก้ไขบัญชีผู้ใช้</a>
                </div>
                <div class="col-auto">

                    <a href="logout.php" class="btn btn-danger">ออกจากระบบ</a>
                </div>
            </div>
        </div>
    </div>

    <?php
    $updatestatus = "SELECT COUNT(*) FROM users";
    $stmt = $conn->query($updatestatus);
    $totalusers = $stmt->fetchColumn();
    $usersData = $conn->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <?php
    $limit = 12;
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users");
    $stmt->execute();
    $totalRows = $stmt->fetchColumn();
    $totalPages = ceil($totalRows / $limit);
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($currentPage - 1) * $limit;

    $order = "ORDER BY user_id DESC";
    if (isset($_GET['work_type_filter'])) {
        $work_type_filter = ($_GET['work_type_filter']);
        if ($work_type_filter === 'ASC') {
            $order = "ORDER BY user_id ASC";
        } elseif ($work_type_filter === 'username_ASC') {
            $order = "ORDER BY username ASC";
        } elseif ($work_type_filter === 'username_DESC') {
            $order = "ORDER BY username DESC";
        }
    }

    $stmt = $conn->prepare("SELECT * FROM users $order LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $usersData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>
    <div class="flex-container">
        <div class="container ">
            <div class="my-3 bg-body  shadow">
                <div class=" justify-content-center ">
                    <div class="border p-0 ">
                        <p class='h4 py-2 px-auto bg-dark border text-white mb-0 text-center '>
                            ข้อมูลผู้ใช้ทั้งหมด </p>
                        <div class="px-4">
                            <div class="">
                                <p class="fs-5 rounded p-1 px-3 m-0 form-control border-0 text-center">
                                    จำนวนบัญชีผู้ใช้ :
                                    <?php echo $totalusers; ?>
                                </p>

                            </div>
                            <div class="ml-auto col-2">
                                <form class="m-0 rounded-top  rounded col-12" method="GET">
                                    <select class="form-control " name="work_type_filter" onchange="this.form.submit()">
                                        <option value="DESC" <?php if (isset($_GET['work_type_filter']) && $_GET['work_type_filter'] === 'DESC')
                                            echo 'selected'; ?>>ลำดับ : มาก -> น้อย
                                        </option>
                                        <option value="ASC" <?php if (isset($_GET['work_type_filter']) && $_GET['work_type_filter'] === 'ASC')
                                            echo 'selected'; ?>>ลำดับ : น้อย -> มาก
                                        </option>
                                    </select>
                                </form>
                            </div>
                            <div class="col">
                                <div class="table-responsive">
                                    <?php if ($totalusers > 0): ?>
                                        <table class="table table-bordered table-sm m-0">
                                            <thead>
                                                <tr class="text-center text-light bg-dark col-12">
                                                    <th class='col-2'>รหัสผู้ใช้</th>
                                                    <th class='col-2'>ชื่อบัญชี</th>
                                                    <th class='col-2'>ชื่อจริง</th>
                                                    <th class='col-2'>นามสกุล</th>
                                                    <th class='col-2'>สถานะ</th>
                                                    <th class='col-2'>จัดการ/สถานะ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($usersData as $row): ?>
                                                    <tr class="text-center">
                                                        <td>
                                                            <?php echo $row['user_id']; ?>
                                                        </td>
                                                        <td class='text-center'>
                                                            <?php echo $row['username']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $row['user_fname']; ?>
                                                        </td>
                                                        <td class='text-center'>
                                                            <?php echo $row['user_lname']; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo $row['role']; ?>
                                                        </td>

                                                        <td class="text-center">
                                                            <div class="btn-group  ">

                                                                <a href="admin-users.php?id=<?php echo $row['user_id']; ?>"
                                                                    class="btn btn-warning">แก้ไขข้อมูล</a>

                                                            </div>
                                                        </td>

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
                        </div>
                        <div class="container my-3 ">
                            <nav aria-label=" Page navigation example">
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
        </div>
    </div>



    <?php include('script.php'); ?>
</body>

</html>