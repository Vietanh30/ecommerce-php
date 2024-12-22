<?php
session_start();

// Xóa tất cả session
session_unset();
session_destroy();

// Chuyển hướng về trang chủ hoặc trang đăng nhập
header("Location: /ecommerce/user_login.php");
exit();
