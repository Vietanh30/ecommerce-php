<?php
include('includes/connect.php');

$response = [];

// Lấy productId từ URL
$productId = isset($_GET['productId']) ? intval($_GET['productId']) : 0;

if ($productId > 0) {
    $query = "SELECT p.id AS product_id, 
                     p.name AS product_name, 
                     p.image, 
                     p.cost_price, 
                     p.selling_price, 
                     p.quantity, 
                     p.description, 
                     p.category_id 
              FROM products p 
              WHERE p.id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $product = $result->fetch_assoc();
        if ($product) {
            // Chuyển đổi cột image thành mảng
            $product['images'] = !empty($product['image']) ? explode(',', $product['image']) : [];
            unset($product['image']); // Loại bỏ trường image nếu không cần thiết

            // Tính giá bán
            $product['discount_price'] = round($product['selling_price']); // Giữ nguyên giá bán

            $response = ['status' => 'success', 'data' => $product];
        } else {
            $response = ['status' => 'error', 'message' => 'Không tìm thấy sản phẩm.'];
        }
    } else {
        $response = ['status' => 'error', 'message' => 'Có lỗi xảy ra khi truy vấn dữ liệu.'];
    }
} else {
    $response = ['status' => 'error', 'message' => 'ID sản phẩm không hợp lệ.'];
}

echo json_encode($response);
exit;
