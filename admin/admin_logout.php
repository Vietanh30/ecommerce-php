<?php
session_start(); // Bắt đầu session
unset($_SESSION['admin_username']); // Xóa username khỏi session
session_destroy(); // Hủy session

// Sử dụng header để chuyển hướng
header("Location: ./login_admin.php");
exit(); // Dừng thực thi script
