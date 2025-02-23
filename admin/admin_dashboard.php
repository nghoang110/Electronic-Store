<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../config.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <span class="navbar-text">
                Xin chào, Admin |
                <a href="logout.php" class="text-light">Đăng xuất</a>
            </span>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                    <a href="admin_dashboard.php" class="list-group-item list-group-item-action active">Trang chủ</a>
                    <a href="manage_products.php" class="list-group-item list-group-item-action">Quản lý Sản phẩm</a>
                    <a href="manage_orders.php" class="list-group-item list-group-item-action">Quản lý Đơn hàng</a>
                    <a href="manage_users.php" class="list-group-item list-group-item-action">Quản lý Khách hàng</a>
                    <a href="manage_categories.php" class="list-group-item list-group-item-action">Quản lý Danh mục</a>
                </div>
            </div>
            <div class="col-md-9">
                <h3>Chào mừng đến với Trang quản trị</h3>
                <p>Chọn một chức năng từ menu bên trái để quản lý.</p>
            </div>
        </div>
    </div>
</body>
</html>