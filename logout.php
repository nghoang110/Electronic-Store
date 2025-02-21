<?php
session_start();
session_destroy();
echo "<script>alert('Bạn đã đăng xuất!'); window.location.href='index.php';</script>";
?>
