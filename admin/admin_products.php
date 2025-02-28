<?php
session_start();
include '../config.php';

// Kiểm tra nếu Admin chưa đăng nhập thì chuyển hướng
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Lấy danh sách sản phẩm từ database
$stmt = $conn->prepare("SELECT products.id, products.name, products.price, products.image, categories.name AS category_name FROM products 
                        JOIN categories ON products.category_id = categories.id 
                        ORDER BY products.id DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Sản Phẩm</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Quản Lý Sản Phẩm</h2>
        <a href="add_product.php" class="btn btn-primary mb-3">Thêm Sản Phẩm</a>

        <table class="table table-bordered">
        <thead class="table-dark">
    <tr>
        <th>ID</th>
        <th>Tên</th>
        <th>Giá</th>
        <th>Danh Mục</th>
        <th>Ảnh</th>
        <th>Tồn Kho</th>
        <th>Hành Động</th>
    </tr>
</thead>
<tbody>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= number_format($row['price'], 0, ',', '.') ?> VNĐ</td>
        <td><?= $row['category_name'] ?></td>
        <td><img src="../uploads/<?= $row['image'] ?>" width="70"></td>
        <td><?= $row['stock'] ?></td> <!-- Thêm số lượng tồn kho -->
        <td>
            <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
            <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xóa sản phẩm này?');">Xóa</a>
        </td>
    </tr>
    <?php } ?>
</tbody>

        </table>

        <a href="admin_dashboard.php" class="btn btn-secondary">Quay lại Trang Quản Trị</a>
    </div>
</body>
</html>
