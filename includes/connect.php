<?php
$servername = "localhost:3307";
$username = "root"; // Tên người dùng
$password = ""; // Mật khẩu (mặc định là trống)
$dbname = "ecommerce";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
