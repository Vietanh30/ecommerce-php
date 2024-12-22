<?php
header('Content-Type: application/json');

// Kết nối cơ sở dữ liệu
include('./includes/connect.php');
include('./functions/common_function.php');
$categoryId = isset($_GET['categoryId']) ? $_GET['categoryId'] : null;

if ($categoryId) {
    $query = "SELECT * FROM products WHERE category_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();

    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    echo json_encode([
        'success' => true,
        'products' => $products
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Danh mục không hợp lệ.'
    ]);
}

$conn->close();
