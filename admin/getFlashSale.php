<?php
include('../includes/connect.php');

$response = [];
$query = "SELECT fs.id, 
                 p.id AS product_id, 
                 p.name AS product_name, 
                 p.image, 
                 p.cost_price, 
                 p.selling_price, 
                 fs.percent, 
                 p.description,  -- Thêm trường description vào truy vấn
                 p.category_id,  -- Thêm trường category_id vào truy vấn
                 fs.start_time, 
                 fs.end_time 
          FROM flashsale fs 
          JOIN products p ON fs.product_id = p.id 
          ORDER BY fs.start_time DESC";

$result = mysqli_query($conn, $query);

if ($result) {
    $flashSales = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Chuyển đổi cột image thành mảng (giả sử hình ảnh được tách bằng dấu phẩy)
        $row['images'] = !empty($row['image']) ? explode(',', $row['image']) : [];
        unset($row['image']); // Loại bỏ trường image nếu không cần thiết

        // Tính giá bán
        $discountedPrice = $row['selling_price'] * (1 - ($row['percent'] / 100));
        $row['discount_price'] = round($discountedPrice); // Làm tròn giá bán

        $flashSales[] = $row;
    }
    $response = ['status' => 'success', 'data' => $flashSales];
} else {
    $response = ['status' => 'error', 'message' => 'Không thể lấy dữ liệu flash sale.'];
}

echo json_encode($response);
exit;
