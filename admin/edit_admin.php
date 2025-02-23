<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
include '../config.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE admins SET username = ?, role = ? WHERE id = ?");
    $stmt->bind_param("ssi", $username, $role, $id);
    
    if ($stmt->execute()) {
        header("Location: manage_admins.php");
        exit();
    } else {
        $error = "Lỗi khi cập nhật Admin!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Sửa Admin</h2>
        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Tên Đăng Nhập</label>
                <input type="text" name="username" class="form-control" value="<?= $admin['username'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Vai Trò</label>
                <select name="role" class="form-control">
                    <option value="admin" <?= $admin['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="editor" <?= $admin['role'] == 'editor' ? 'selected' : '' ?>>Editor</option>
                </select>
            </div>
            <button type="submit" class="btn btn-warning">Cập Nhật</button>
        </form>
    </div>
</body>
</html>
