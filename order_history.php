<?php
session_start();
include 'config.php'; // Kết nối database

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy danh sách đơn hàng của user
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử đơn hàng</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Lịch sử đơn hàng</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Đơn</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th>Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo date("d/m/Y", strtotime($row['created_at'])); ?></td>
                    <td><?php echo number_format($row['total_price'], 0, ',', '.'); ?> VNĐ</td>
                    <td><?php echo $row['status']; ?></td>
                    <td><a href="order_details.php?order_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-dark">Xem</a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-secondary">Quay lại</a>
    </div>
</body>
</html>
