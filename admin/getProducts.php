<?php
include('../includes/connect.php');

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$category = isset($_GET['category']) ? intval($_GET['category']) : 0;

$query = "SELECT * FROM `products` WHERE 1";

if ($category > 0) {
    $query .= " AND category_id = $category";
}

if (!empty($search)) {
    $query .= " AND name LIKE '%$search%'";
}

$query .= " ORDER BY id DESC";

$result = mysqli_query($conn, $query);

$response = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $response[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'image' => "./product_images/" . explode(',', $row['image'])[0],
            'cost_price' => number_format($row['cost_price'], 0, '.', '.'),
            'selling_price' => number_format($row['selling_price'], 0, '.', '.'),
            'quantity' => $row['quantity'], // Thêm trường mô tả
            'description' => $row['description'], // Thêm trường mô tả
            'category_id' => $row['category_id'], // Thêm trường ID danh mục
            // Thêm bất kỳ trường nào khác bạn muốn trả về
        ];
    }
    echo json_encode(['status' => 'success', 'data' => $response]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy sản phẩm nào.']);
}
