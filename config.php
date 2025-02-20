<?php
$servername = "localhost";
$username = "root";  // Thay bằng user của bạn
$password = "";      // Thay bằng mật khẩu của bạn
$database = "electronics_store";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
