<!DOCTYPE html>
<html lang="en">

<!-- header  -->
<?php
include("header.php");
$book_id = $_GET['id'];
?>
<!-- body -->

<body
    style="background-color:#2F5597; background-image: url('img/bg.jpg'); background-position: center; background-size: cover; ">
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

    $book_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM books WHERE `book_id` =  '$book_id' ");
    $stmt->execute();
    $bookData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("SELECT * FROM book_types ");
    $stmt->execute();
    $typeData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>
    <!-- content -->

    <div class="container  bg-white shadow border p-0 col-8 my-2 rounded ">
        <p class='h4 py-2  bg-dark border text-white  mb-0 text-center  rounded-top'>ระบบแก้ไขข้อมูลหนังสือ</p>
        <div class="">
            <?php foreach ($bookData as $row): ?>
                <form class="container p-4 align-content-center " action="php/update-book.php" name="addwp" method="POST">

                    <input class="form-control" type="hidden" name="book_id" id="book_id"
                        value="<?php echo $row['book_id']; ?>" required>
                    <div class="form-group">
                        <label for="book_name">ชื่อหนังสือ</label>
                        <input class="form-control" type="text" name="book_name" id="book_name"
                            value="<?php echo $row['book_name']; ?>" required>
                    </div>

                    <div class=" form-group">
                        <label for="author">ผู้แต่ง</label>
                        <input class="form-control" type="text" name="author" id="author"
                            value="<?php echo $row['author']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="publisher">สำนักพิมพ์</label>
                        <input class="form-control" type="text" name="publisher" id="publisher"
                            value="<?php echo $row['publisher']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="book_type">ประเภทหนังสือ</label>
                        <select class="form-control" name="book_type" id="book_type">
                            <?php foreach ($typeData as $type): ?>
                                <option value="<?php echo $type['type_id']; ?>" <?php
                                   if ($row['type_id'] == $type['type_id']) {
                                       echo ' selected';
                                   } ?>>
                                    <?php echo $type['type_name'];
                                    ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="bookvalue">จำนวน</label>
                        <input class="form-control" type="number" name="bookvalue" id="bookvalue"
                            value="<?php echo $row['bookvalue']; ?>" required>
                    </div>

                    <button class="mt-3 btn btn-success w-100" type="submit" name="submit" value="Submit">
                        บันทึกการแก้ไข</button>
                    <a href="#" onclick="confirmDelete(<?php echo $row['book_id']; ?>)"
                        class="mt-1 btn btn-warning w-100">ลบหนังสือ</a>
                    <a href="manage-book.php" class="mt-1 btn btn-danger w-100">ยกเลิก</a>
                </form>
            <?php endforeach; ?>
        </div>
        <?php
        include 'script.php';
        ?>

</body>

</html>


<script>
    // เลือก input element โดยใช้ ID
    const bookValueInput = document.getElementById('bookvalue');


    bookValueInput.addEventListener('change', function () {

        if (this.value < 1) {
            alert('ใส่จำนวนหนังสือให้ถูกต้อง');
            this.value = 1;
        }
    });

    function confirmDelete(bookId) {
        if (confirm("คุณแน่ใจหรือไม่ว่าต้องการลบหนังสือนี้?")) {
            window.location.href = `php/del-book.php?id=${bookId}`;
        } else {
        }
    }
</script>