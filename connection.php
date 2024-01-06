<<<<<<< HEAD
<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "librarydb";

$connection = new mysqli($server, $username, $password, $database);

if ($connection->connect_error) {
    echo "Connection Failed!";
    die("Connection failed: " . $connection->connect_error);
} else {
}


try {
    $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES utf8");
} catch (PDOException $e) {
    echo "เชื่อมต่อฐานข้อมูลล้มเหลว! : " . $e->getMessage();
}


=======
<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "librarydb";

$connection = new mysqli($server, $username, $password, $database);

if ($connection->connect_error) {
    echo "Connection Failed!";
    die("Connection failed: " . $connection->connect_error);
} else {
}


try {
    $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES utf8");
} catch (PDOException $e) {
    echo "เชื่อมต่อฐานข้อมูลล้มเหลว! : " . $e->getMessage();
}


>>>>>>> e8e6f18fc4e84e741c7390069d2e2a561dc90624
?>