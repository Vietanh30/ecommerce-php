<?php
include('../includes/connect.php');
$response = [];

try {
    if (empty($_POST['flash_sale_id'])) {
        throw new Exception('ID của chương trình khuyến mãi không hợp lệ.');
    }

    $flashSaleId = $_POST['flash_sale_id'];

    // Lấy thông tin chương trình khuyến mãi hiện tại
    $currentQuery = "SELECT * FROM flashsale WHERE id = '$flashSaleId'";
    $currentResult = mysqli_query($conn, $currentQuery);

    if (mysqli_num_rows($currentResult) === 0) {
        throw new Exception('Chương trình khuyến mãi không tồn tại.');
    }

    $currentFlashSale = mysqli_fetch_assoc($currentResult);

    // Sử dụng các giá trị cũ nếu không có giá trị mới được gửi
    $product_ids = isset($_POST['ids']) && is_array($_POST['ids']) && count($_POST['ids']) > 0
        ? implode(',', array_unique($_POST['ids']))
        : $currentFlashSale['product_id'];

    $start_time = !empty($_POST['start_time']) ? $_POST['start_time'] : $currentFlashSale['start_time'];
    $end_time = !empty($_POST['end_time']) ? $_POST['end_time'] : $currentFlashSale['end_time'];

    if (empty($_POST['percent']) || !is_numeric($_POST['percent']) || $_POST['percent'] < 0 || $_POST['percent'] > 100) {
        throw new Exception('Giá trị khuyến mãi phải là số trong khoảng 0-100.');
    }

    $percent = $_POST['percent'];

    // Cập nhật thông tin chương trình khuyến mãi
    $updateQuery = "UPDATE flashsale SET start_time = '$start_time', end_time = '$end_time', percent = '$percent', product_id = '$product_ids' WHERE id = '$flashSaleId'";
    if (!mysqli_query($conn, $updateQuery)) {
        throw new Exception('Cập nhật flashsale không thành công.');
    }

    $response = ['status' => 'success', 'message' => 'Cập nhật chương trình khuyến mãi thành công.'];
} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

echo json_encode($response);
exit;
