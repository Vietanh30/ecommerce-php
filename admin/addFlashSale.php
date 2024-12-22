<?php
include('../includes/connect.php');
$response = [];

try {
    if (isset($_POST['flash_sale_product']) && is_numeric($_POST['flash_sale_product'])) {
        $product_id = $_POST['flash_sale_product'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
    } else {
        throw new Exception('Vui lòng chọn một sản phẩm hợp lệ.');
    }

    if (empty($start_time) || empty($end_time)) {
        throw new Exception('Thời gian bắt đầu và kết thúc không được để trống.');
    }

    if (empty($_POST['percent']) || !is_numeric($_POST['percent']) || $_POST['percent'] < 0 || $_POST['percent'] > 100) {
        throw new Exception('Giá trị khuyến mãi phải là số trong khoảng 0-100.');
    }

    $percent = $_POST['percent'];

    $insertQuery = "INSERT INTO flashsale (product_id, start_time, end_time, percent) VALUES ('$product_id', '$start_time', '$end_time', '$percent')";
    if (!mysqli_query($conn, $insertQuery)) {
        throw new Exception('Thêm mới flashsale không thành công.');
    }

    $response = ['status' => 'success', 'message' => 'Thêm mới chương trình khuyến mãi thành công.'];
} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

echo json_encode($response);
exit;
