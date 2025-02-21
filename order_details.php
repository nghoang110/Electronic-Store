<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header("Location: order_history.php");
    exit();
}

$order_id = $_GET['order_id'];

// Lấy thông tin đơn hàng
$sql = "SELECT * FROM orders WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

// Lấy danh sách sản phẩm trong đơn hàng
$sql = "SELECT p.name, od.quantity, od.price FROM order_details od 
        JOIN products p ON od.product_id = p.id WHERE od.order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Chi tiết đơn hàng #<?php echo $order_id; ?></h2>
        <p><strong>Ngày đặt:</strong> <?php echo date("d/m/Y", strtotime($order['created_at'])); ?></p>
        <p><strong>Trạng thái:</strong> <?php echo $order['status']; ?></p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $items->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo number_format($row['price'], 0, ',', '.'); ?> VNĐ</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <p><strong>Tổng tiền:</strong> <?php echo number_format($order['total_price'], 0, ',', '.'); ?> VNĐ</p>
        <a href="order_history.php" class="btn btn-secondary">Quay lại</a>
    </div>
</body>
</html>
