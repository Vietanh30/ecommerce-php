<?php
include('../includes/connect.php');
include('../functions/common_function.php');

// Thêm mã để lấy chi tiết sản phẩm nếu cần
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $response = viewDetails($product_id); // Gọi hàm viewDetails với product_id
}
echo json_encode($response);
