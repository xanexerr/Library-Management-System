<!DOCTYPE html>
<html lang="en">

<!-- header  -->
<?php
include("header.php")
    ?>
<!-- body -->

<body
    style="background-color:#2F5597; background-image: url('img/bg.jpg'); background-position: center; background-size:auto; ">
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
    } else {
        if ($_SESSION["role"] !== 'librarian') {
            echo '<script>';
            echo 'alert("คุณไม่มีสิทธิเข้าถึง!");';
            echo 'window.location.href = "index.php";';
            echo '</script>';
            exit();
        }
    }

    $stmt = $conn->prepare("SELECT COUNT(*) FROM book_types ");
    $stmt->execute();

    $stmt = $conn->prepare("SELECT * FROM book_types ");
    $stmt->execute();
    $typeData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmtbk = $conn->prepare("SELECT * FROM books WHERE borrowstatus < bookvalue;");
    $stmtbk->execute();
    $bookData = $stmtbk->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <!-- content -->

    <div class="container  bg-white shadow border p-0 col-8 my-2 rounded ">
        <p class='h4 py-2  bg-dark border text-white  mb-0 text-center  rounded-top'>ระบบยืมหนังสือ</p>
        <div class="">

            <form class="container p-4 align-content-center " action="php/borrowing.php" name="addwp" method="POST">

                <div class="form-group">
                    <label for="book_name">ชื่อหนังสือ</label>
                    <select name="book_id" id="book_id" class="form-control" required>
                        <?php foreach ($bookData as $book): ?>
                            <option value="<?php echo $book['book_id']; ?>">
                                <?php echo $book['book_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="user_id">รหัสสมาชิก</label>
                    <input class="form-control" type="text" name="user_id" id="user_id" required>
                </div>

                <div class="form-group">
                    <label for="date">วันที่ยืม (วัน/เดือน/ปี)</label>
                    <input type="text" class="form-control" name="date" id="date" value="<?php echo date('d/m/Y'); ?>"
                        required>
                </div>

                <button class="btn btn-success form-control mt-3" type="submit" name="submit" value="Submit">
                    บันทึก</button>
                <a href="librarian_main.php" class="mt-1 btn btn-danger w-100">ยกเลิก</a>
            </form>

        </div>
        <?php
        include 'script.php';
        ?>

</body>

</html>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#date", {
            dateFormat: "d/m/Y",
        });
    });
</script>