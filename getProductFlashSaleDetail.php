<?php
include('includes/connect.php');

$response = [];

// Lấy productId từ URL
$productId = isset($_GET['productId']) ? intval($_GET['productId']) : 0;

if ($productId > 0) {
    $query = "SELECT fs.id, 
                     p.id AS product_id, 
                     p.name AS product_name, 
                     p.image, 
                     p.cost_price, 
                     p.selling_price, 
                     p.quantity, 
                     fs.percent, 
                     p.description, 
                     p.category_id, 
                     fs.start_time, 
                     fs.end_time 
              FROM flashsale fs 
              JOIN products p ON fs.product_id = p.id 
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
            $discountedPrice = $product['cost_price'] * (1 - ($product['percent'] / 100));
            $product['discount_price'] = round($discountedPrice); // Làm tròn giá bán

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
