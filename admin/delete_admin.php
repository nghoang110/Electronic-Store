<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../config.php';

// Kiểm tra nếu có ID Admin cần xóa
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Không cho phép xóa chính tài khoản Admin đang đăng nhập
    if ($id == $_SESSION['admin_id']) {
        echo "<script>alert('Bạn không thể xóa tài khoản đang đăng nhập!'); window.location.href='manage_admins.php';</script>";
        exit();
    }

    // Xóa Admin từ database
    $stmt = $conn->prepare("DELETE FROM admins WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Xóa thành công!'); window.location.href='manage_admins.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi xóa Admin!'); window.location.href='manage_admins.php';</script>";
    }
} else {
    header("Location: manage_admins.php");
    exit();
}
?>
