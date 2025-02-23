<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Lấy tên ảnh để xóa
    $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($image);
    $stmt->fetch();
    $stmt->close();

    // Xóa ảnh từ thư mục uploads
    if (!empty($image)) {
        unlink("../uploads/" . $image);
    }

    // Xóa sản phẩm từ database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: manage_products.php");
        exit();
    } else {
        echo "Lỗi khi xóa sản phẩm!";
    }
}
?>
