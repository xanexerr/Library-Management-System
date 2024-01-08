<!DOCTYPE html>
<html lang="en">

<!-- header  -->
<?php
include("header.php")
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

    $stmt = $conn->prepare("SELECT COUNT(*) FROM book_types ");
    $stmt->execute();
    $totalRows = $stmt->fetchColumn();

    $stmt = $conn->prepare("SELECT * FROM book_types ");
    $stmt->execute();
    $typeData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>
    <!-- content -->

    <div class="container  bg-white shadow border p-0 col-8 my-2 rounded ">
        <p class='h4 py-2  bg-dark border text-white  mb-0 text-center  rounded-top'>ระบบเพิ่มหนังสือใหม่</p>
        <div class="">

            <form class="container p-4 align-content-center " action="php/add-book.php" name="addwp" method="POST">

                <div class="form-group">
                    <label for="book_name">ชื่อหนังสือ</label>
                    <input class="form-control" type="text" name="book_name" id="book_name" required>
                </div>

                <div class="form-group">
                    <label for="author">ผู้แต่ง</label>
                    <input class="form-control" type="text" name="author" id="author" required>
                </div>

                <div class="form-group">
                    <label for="publisher">สำนักพิมพ์</label>
                    <input class="form-control" type="text" name="publisher" id="publisher" required>
                </div>

                <div class="form-group">
                    <label for="book_type">ประเภทหนังสือ</label>
                    <select class="form-control" name="book_type" id="book_type">
                        <?php foreach ($typeData as $type): ?>
                            <option value="<?php echo $type['type_id']; ?>">
                                <?php echo $type['type_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <div class="form-group">
                    <label for="bookvalue">จำนวน</label>
                    <input class="form-control" type="number" name="bookvalue" id="bookvalue" value="1" required>
                </div>

                <button class="btn btn-success form-control mt-3" type="submit" name="submit" value="Submit">
                    บันทึก</button>
                <a href="lb-book.php" class="mt-1 btn btn-danger w-100">ยกเลิก</a>
            </form>

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
</script>