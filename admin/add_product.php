<?php
session_start();
include '../config.php';

// Kiểm tra nếu Admin chưa đăng nhập thì chuyển hướng
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Lấy danh sách danh mục sản phẩm
$category_stmt = $conn->prepare("SELECT * FROM categories");
$category_stmt->execute();
$categories = $category_stmt->get_result();

// Xử lý thêm sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];

    // Upload ảnh sản phẩm
    $image = $_FILES['image']['name'];
    $target = "../uploads/" . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    // Thêm sản phẩm vào database
    $stmt = $conn->prepare("INSERT INTO products (name, price, image, category_id, description) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsis", $name, $price, $image, $category_id, $description);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Thêm sản phẩm thành công!";
        header("Location: admin_products.php");
        exit();
    } else {
        $error_message = "Lỗi khi thêm sản phẩm.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Sản Phẩm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Thêm Sản Phẩm</h2>

        <?php if (isset($error_message)) { ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php } ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Tên Sản Phẩm</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Giá</label>
                <input type="number" name="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Danh Mục</label>
                <select name="category_id" class="form-control" required>
                    <?php while ($cat = $categories->fetch_assoc()) { ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Mô Tả</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Hình Ảnh</label>
                <input type="file" name="image" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Thêm Sản Phẩm</button>
            <a href="admin_products.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</body>
</html>
