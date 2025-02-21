<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ | Điện Tử Store</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<!-- Thanh điều hướng -->
<?php
session_start();
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">ELECTRO SHOP</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="products.php">Sản phẩm</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">Giới thiệu</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Liên hệ</a></li>
                
                <!-- Nếu đã đăng nhập -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold text-primary" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            Xin chào, <?= $_SESSION['user_name'] ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="order_history.php">Lịch sử đơn hàng</a></li>
                            <li><a class="dropdown-item" href="profile.php">Thông tin cá nhân</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger fw-bold" href="logout.php">Đăng xuất</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold text-dark" href="cart.php">
                            🛒 Giỏ hàng <span class="badge bg-danger">3</span> <!-- Hiển thị số sản phẩm trong giỏ -->
                        </a>
                    </li>

                <!-- Nếu chưa đăng nhập -->
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link fw-bold text-dark" href="login.php">ĐĂNG NHẬP</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold text-dark" href="register.php">ĐĂNG KÝ</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>



<!-- Banner chính -->
<header class="hero-section">
    <div class="container text-center">
        <h1 class="display-3 fw-bold text-white">Khám Phá Công Nghệ Mới</h1>
        <p class="lead text-white">Điện thoại, Laptop, Phụ kiện chính hãng</p>
        <a href="products.php" class="btn btn-light btn-lg">Mua ngay</a>
    </div>
</header>

<!-- Danh mục sản phẩm -->
<section class="container my-5">
    <h2 class="text-center fw-bold">Danh mục sản phẩm</h2>
    <div class="row text-center mt-4">
        <div class="col-md-4">
            <img src="assets/img/laptop.jpg" class="img-fluid category-img">
            <h5 class="mt-3">Laptop</h5>
        </div>
        <div class="col-md-4">
            <img src="assets/img/phone.jpg" class="img-fluid category-img">
            <h5 class="mt-3">Điện thoại</h5>
        </div>
        <div class="col-md-4">
            <img src="assets/img/accessories.jpg" class="img-fluid category-img">
            <h5 class="mt-3">Phụ kiện</h5>
        </div>
    </div>
</section>

<!-- Danh sách sản phẩm -->
<section class="container my-5">
    <h2 class="text-center fw-bold">Sản phẩm nổi bật</h2>
    <div class="row mt-4">
        <?php
        $sql = "SELECT * FROM products ORDER BY created_at DESC LIMIT 4";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo '
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <img src="assets/img/'.$row['image'].'" class="card-img-top" alt="'.$row['name'].'">
                    <div class="card-body text-center">
                        <h5 class="card-title">'.$row['name'].'</h5>
                        <p class="card-text">'.number_format($row['price'], 0, ',', '.').' VNĐ</p>
                        <a href="#" class="btn btn-dark">Thêm vào giỏ</a>
                    </div>
                </div>
            </div>';
        }
        ?>
    </div>
</section>

<!-- Chân trang -->
<footer class="bg-dark text-white text-center py-3">
    <p>© 2025 Điện Tử Store. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
