<?php
include 'header.php';

session_start();
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
<div class="bg-primary">
    <div
        class="container d-flex flex-wrap justify-content-center py-3  mx-auto border-bottom text-white bg-primary px-3">
        <a class="d-flex align-items-center  mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
            <span class="fs-4 text-white m-1 text-shadow">
                ระบบบรรณารักษณ์
            </span></a>
        <div class="rounded d-flex align-items-center mb-md-0 mx-1 link-body-emphasis text-decoration-none">
            <?php
            if (!isset($_SESSION["username"])) {
                echo '<script>';
                echo 'Swal.fire("คุณยังไม่ได้เข้าสู่ระบบ", { icon: "warning" }).then(() => { window.location.href = "login.php"; });';
                echo '</script>';
                exit();
            } else {
                // Check for the appropriate role or any other necessary conditions
                if ($_SESSION["role"] !== 'librarian') {
                    echo '<script>';
                    echo 'Swal.fire("คุณไม่มีสิทธิเข้าถึง!", { icon: "error" }).then(() => { window.location.href = "index.php"; });';
                    echo '</script>';
                    exit();
                }
            }
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
<div class="navbar bg-dark">
    <div class="container">
        <div class="btn-group btn-group-toggle mx-auto">
            <div class="col-auto">
                <a class="btn btn-success  rounded-0 px-4 border-dark " href="librarian_main.php">หน้าแรก</a>
            </div>
            <div class="col-auto">
                <a class="btn btn-warning  rounded-0 px-4 border-dark" href="librarian-users.php">แก้ไขบัญชีผู้ใช้</a>
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
                <a class="btn btn-warning  rounded-0 px-4 border-dark" href="borrowhistory.php">ข้อมูลการยืมหนังสือ</a>
            </div>
            <div class="col-auto">
                <a href="logout.php" class="btn btn-danger border-dark rounded-0 px-4 ">ออกจากระบบ</a>
            </div>
        </div>
    </div>
</div>
<?php
require('connection.php');

$searchQuery = isset($_GET['search_query']) ? $_GET['search_query'] : '';
$limit = 10;
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($currentPage - 1) * $limit;
if (isset($_GET['book_type']) && !empty($_GET['book_type'])) {
    $user_type = $_GET['book_type'];

    if (!empty($searchQuery)) {
        $search = '%' . $searchQuery . '%';

        // Count total rows based on search criteria
        $stmt = $conn->prepare("SELECT COUNT(*) as count 
            FROM books 
            LEFT JOIN book_types ON books.type_id = book_types.type_id 
            WHERE (books.book_id LIKE :search_query 
                OR books.book_name LIKE :search_query 
                OR books.author LIKE :search_query 
                OR books.publisher LIKE :search_query)
                AND books.type_id = :book_type");

        $stmt->bindValue(':search_query', $search, PDO::PARAM_STR);
        $stmt->bindValue(':book_type', $user_type, PDO::PARAM_INT);
        $stmt->execute();
        $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Retrieve book data
        $stmt = $conn->prepare("SELECT books.*, book_types.type_name 
            FROM books 
            LEFT JOIN book_types ON books.type_id = book_types.type_id 
            WHERE (books.book_id LIKE :search_query 
                OR books.book_name LIKE :search_query 
                OR books.author LIKE :search_query 
                OR books.publisher LIKE :search_query)
                AND books.type_id = :book_type
            ORDER BY books.book_id DESC 
            LIMIT :limit OFFSET :offset");

        $stmt->bindValue(':search_query', $search, PDO::PARAM_STR);
        $stmt->bindValue(':book_type', $user_type, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $booksData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $countStmt = $conn->prepare("SELECT COUNT(*) as count FROM books WHERE type_id = :book_type");
        $countStmt->bindValue(':book_type', $user_type, PDO::PARAM_INT);
        $countStmt->execute();
        $totalRows = $countStmt->fetch(PDO::FETCH_ASSOC)['count'];

        $stmt = $conn->prepare("SELECT *
        FROM books  
        WHERE type_id = :book_type
        ORDER BY book_id DESC 
        LIMIT :limit OFFSET :offset");

        $stmt->bindValue(':book_type', $user_type, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $booksData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    if (!empty($searchQuery)) {
        $search = '%' . $searchQuery . '%';
        // Count total rows based on search criteria
        $stmt = $conn->prepare("SELECT COUNT(*) as count 
                        FROM books 
                        LEFT JOIN book_types ON books.type_id = book_types.type_id 
                        WHERE (books.book_id LIKE :search_query 
                            OR books.book_name LIKE :search_query 
                            OR books.author LIKE :search_query 
                            OR book_types.type_name LIKE :search_query
                            OR books.publisher LIKE :search_query
                            )");
        $stmt->bindValue(':search_query', $search, PDO::PARAM_STR);
        $stmt->execute();
        $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        // Retrieve book data
        $stmt = $conn->prepare("SELECT books.*, book_types.type_name 
                        FROM books 
                        LEFT JOIN book_types ON books.type_id = book_types.type_id 
                        WHERE (books.book_id LIKE :search_query 
                            OR books.book_name LIKE :search_query 
                            OR books.author LIKE :search_query 
                            OR book_types.type_name LIKE :search_query
                            OR books.publisher LIKE :search_query
                            ) 
                        ORDER BY books.book_id DESC 
                        LIMIT :limit OFFSET :offset");

        $stmt->bindValue(':search_query', $search, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $booksData = $stmt->fetchAll(PDO::FETCH_ASSOC);


    } else {
        $countStmt = $conn->prepare("SELECT COUNT(*) as count FROM books");
        $countStmt->execute();
        $totalRows = $countStmt->fetch(PDO::FETCH_ASSOC)['count'];

        $stmt = $conn->prepare("SELECT *
                            FROM books  
                            ORDER BY book_id DESC 
                            LIMIT :limit OFFSET :offset");
    }
}

$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$booksData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalPages = ceil($totalRows / $limit);

$stmt = $conn->prepare("SELECT COUNT(*) FROM book_types ");
$stmt->execute();

$stmt = $conn->prepare("SELECT * FROM book_types ");
$stmt->execute();
$typeData = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body style="background-color:#2F5597; 
    background-image: url('img/bg.jpg'); ">
    <div class="flex-container">
        <div class="container ">
            <div class="my-3 bg-body  shadow ">
                <!-- ... (Your existing HTML structure before displaying book information) -->
                <div class="">
                </div>
                <div class="col">
                    <div class="">
                        <p class="fs-4 p-2 text-center bg-dark text-white m-0">
                            ข้อมูลหนังสือในระบบ
                        </p>
                    </div>
                    <form class="m-0 rounded-top  rounded-0 col-md-12 " method="GET">
                        <div
                            class="input-group container bg-secondary px-4 p-2 py-3 mx-auto col-10 row justify-content-md-center ">
                            <div class="form-group col-md-2 p-0">
                                <select class=" form-control rounded-0 ml-3 col-2 bg-primary text-white "
                                    name="book_type" id="book_type" onchange="this.form.submit()">
                                    <option value="">ทั้งหมด</option>
                                    <?php foreach ($typeData as $type): ?>
                                        <option value="<?php echo $type['type_id']; ?>" <?php if (isset($_GET['book_type']) && !empty($_GET['book_type']) && $type['type_id'] == $user_type) {
                                               echo "selected";
                                           } ?>>
                                            <?php echo $type['type_name']; ?>
                                        </option>

                                        <?php echo $type['type_name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group col-md-8 p-0">
                                <input type="text" class=" rounded-0  ml-3  form-control" placeholder="ค้นหา...."
                                    name="search_query" value="<?php if (isset($searchQuery)) {
                                        echo $searchQuery;
                                    }
                                    ?>">
                            </div>

                            <button class="btn btn-primary rounded-0 px-3 mr-3 col-md-2" type="submit"
                                style="font-size: 1em;">

                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor"
                                    class="bi bi-search" viewBox="0 0 16 16" style="vertical-align: middle;">
                                    <path
                                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                                </svg> ค้นหา
                            </button>
                        </div>
                    </form>
                    <div class="table-responsive px-3 pb-3">

                        <table class="table table-bordered table-sm m-0 ">
                            <thead>
                                <tr class="text-center text-light bg-dark col-10">
                                    <th class='col-1'>รหัสหนังสือ</th>
                                    <th class='col-2'>ชื่อหนังสือ</th>
                                    <th class='col-2'>ประเภทหนังสือ</th>
                                    <th class='col-2'>ผู้แต่ง</th>
                                    <th class='col-1'>สำนักพิมพ์</th>
                                    </th>
                                    <th class='col-1'>จำนวนนหนังสือ</th>
                                    <th class='col-1'>จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($booksData as $row): ?>
                                    <tr class="text-center">
                                        <td>
                                            <?php echo $row['book_id']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['book_name']; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $type_id = $row['type_id'];
                                            $typeStmt = $conn->prepare("SELECT type_name FROM book_types WHERE type_id = :type_id");
                                            $typeStmt->bindParam(':type_id', $type_id, PDO::PARAM_INT);
                                            $typeStmt->execute();
                                            $typeRow = $typeStmt->fetch(PDO::FETCH_ASSOC);
                                            if ($typeRow) {
                                                echo $typeRow['type_name'];
                                            } else {

                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <?php echo $row['author']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['publisher']; ?>
                                        </td>
                                        <td>
                                            <?php echo $row['bookvalue']; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group  ">
                                                <a class="btn btn-sm btn-warning rounded-0 px-4"
                                                    href="book-edit.php?id=<?php echo $row['book_id']; ?>">แก้ไข
                                                </a>

                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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
                        <a href="lb-book.php" class="mt-1 btn btn-danger w-100">กลับ</a>
                        </a>
                    </div>






                </div>

            </div>
        </div>

    </div>

</body>