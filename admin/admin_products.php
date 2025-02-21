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
                    <th>Hình Ảnh</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Giá</th>
                    <th>Danh Mục</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><img src="../uploads/<?php echo $row['image']; ?>" width="50"></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo number_format($row['price'], 0, ',', '.'); ?> đ</td>
                        <td><?php echo $row['category_name']; ?></td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Sửa</a>
                            <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <a href="admin_dashboard.php" class="btn btn-secondary">Quay lại Trang Quản Trị</a>
    </div>
</body>
</html>
