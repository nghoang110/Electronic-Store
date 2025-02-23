<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../config.php';

// Xử lý khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $stock = $_POST['stock'];
    $featured = isset($_POST['featured']) ? 1 : 0; // Kiểm tra checkbox "Sản phẩm nổi bật"

    // Xử lý upload ảnh
    $image = $_FILES['image']['name'];
    $target_dir = "../assets/img/";
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

    // Chèn sản phẩm vào CSDL
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, category_id, image, stock, featured) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdssii", $name, $description, $price, $category_id, $image, $stock, $featured);
    
    if ($stmt->execute()) {
        header("Location: manage_products.php");
        exit();
    } else {
        $error = "Lỗi khi thêm sản phẩm!";
    }
}

// Lấy danh sách danh mục từ CSDL
$categories = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sản Phẩm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Thêm Sản Phẩm Mới</h2>

        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php } ?>
        
        <form method="POST" enctype="multipart/form-data" class="border p-4 rounded shadow bg-light">
            <div class="mb-3">
                <label class="form-label fw-bold">Tên Sản Phẩm</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Mô Tả</label>
                <textarea name="description" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Giá (VNĐ)</label>
                <input type="number" name="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Danh Mục</label>
                <select name="category_id" class="form-control">
                    <?php while ($row = $categories->fetch_assoc()) { ?>
                        <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Số lượng tồn kho</label>
                <input type="number" name="stock" class="form-control" required min="0">
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Ảnh Sản Phẩm</label>
                <input type="file" name="image" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Sản phẩm Nổi bật?</label>
                <input type="checkbox" name="featured" value="1">
            </div>
            <button type="submit" class="btn btn-success">Thêm Sản Phẩm</button>
            <a href="manage_products.php" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</body>
</html>
