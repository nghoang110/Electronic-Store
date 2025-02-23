<?php
include '../config.php';
session_start();

// Kiểm tra xem Admin đã đăng nhập chưa
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Kiểm tra ID sản phẩm
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Lấy dữ liệu sản phẩm từ database
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Nếu sản phẩm không tồn tại, chuyển hướng về trang quản lý
    if (!$product) {
        header("Location: manage_products.php");
        exit();
    }
}

// Xử lý khi form được gửi đi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];
    $stock = $_POST['stock'];
    $featured = isset($_POST['featured']) ? 1 : 0; // Nếu checkbox được chọn thì giá trị là 1, ngược lại là 0
    
    // Kiểm tra nếu có ảnh mới
    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        $target = "../uploads/" . basename($image);
        
        // Chỉ chấp nhận file ảnh
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = pathinfo($image, PATHINFO_EXTENSION);
        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            $error = "Chỉ chấp nhận file JPG, PNG, GIF.";
        } else {
            move_uploaded_file($_FILES['image']['tmp_name'], $target);
        }
    } else {
        $image = $product['image']; // Giữ ảnh cũ nếu không tải ảnh mới
    }

    // Cập nhật thông tin sản phẩm
    $sql = "UPDATE products SET name=?, description=?, price=?, category_id=?, stock=?, image=?, featured=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdissii", $name, $description, $price, $category_id, $stock, $image, $featured, $product_id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Cập nhật sản phẩm thành công!";
        header("Location: manage_products.php");
        exit();
    } else {
        $error = "Lỗi khi cập nhật sản phẩm.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sản phẩm</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Chỉnh sửa sản phẩm</h2>

    <?php if (isset($error)) : ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data" class="p-4 border rounded bg-light">
        <div class="mb-3">
            <label for="name" class="form-label">Tên sản phẩm</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= $product['name'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea class="form-control" id="description" name="description" rows="3"><?= $product['description'] ?></textarea>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá (VNĐ)</label>
            <input type="number" class="form-control" id="price" name="price" value="<?= $product['price'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Danh mục</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <option value="">Chọn danh mục</option>
                <?php
                $category_sql = "SELECT * FROM categories";
                $category_result = $conn->query($category_sql);
                while ($row = $category_result->fetch_assoc()) :
                ?>
                    <option value="<?= $row['id'] ?>" <?= ($product['category_id'] == $row['id']) ? 'selected' : '' ?>>
                        <?= $row['name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Số lượng hàng</label>
            <input type="number" class="form-control" id="stock" name="stock" value="<?= $product['stock'] ?>" required>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Hình ảnh sản phẩm</label>
            <input type="file" class="form-control" id="image" name="image">
            <p class="mt-2">Ảnh hiện tại:</p>
            <img src="../assets/img/<?= $product['image'] ?>" width="120" alt="Ảnh sản phẩm">
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="featured" name="featured" <?= ($product['featured'] == 1) ? 'checked' : '' ?>>
            <label class="form-check-label" for="featured">Sản phẩm nổi bật</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Cập nhật</button>
    </form>

    <div class="text-center mt-3">
        <a href="manage_products.php" class="btn btn-secondary">Quay lại</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

