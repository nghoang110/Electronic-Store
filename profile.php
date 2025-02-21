<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy thông tin cá nhân từ database
$sql = "SELECT fullname, email, phone, address FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Xử lý khi người dùng cập nhật thông tin
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $update_sql = "UPDATE users SET fullname=?, phone=?, address=? WHERE id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssi", $fullname, $phone, $address, $user_id);
    
    if ($stmt->execute()) {
        $success_message = "Cập nhật thông tin thành công!";
        // Cập nhật lại dữ liệu sau khi chỉnh sửa
        $user['fullname'] = $fullname;
        $user['phone'] = $phone;
        $user['address'] = $address;
    } else {
        $error_message = "Lỗi cập nhật thông tin!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin cá nhân</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Thông tin cá nhân</h2>

        <?php if (isset($success_message)) { ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php } ?>

        <?php if (isset($error_message)) { ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php } ?>

        <form method="POST" class="border p-4 rounded shadow bg-light">
            <div class="mb-3">
                <label class="form-label">Họ và Tên</label>
                <input type="text" name="fullname" class="form-control" value="<?php echo $user['fullname']; ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" value="<?php echo $user['email']; ?>" disabled>
            </div>
            <div class="mb-3">
                <label class="form-label">Số điện thoại</label>
                <input type="text" name="phone" class="form-control" value="<?php echo $user['phone']; ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Địa chỉ</label>
                <textarea name="address" class="form-control"><?php echo $user['address']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-dark w-100">Lưu thay đổi</button>
        </form>
        
        <a href="index.php" class="btn btn-secondary mt-3">Quay lại</a>
    </div>
</body>
</html>
