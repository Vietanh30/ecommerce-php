<?php
// Kết nối tới cơ sở dữ liệu
include('../includes/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = [];
    try {
        // Lấy ID sản phẩm từ yêu cầu
        $product_id = $_POST['product_id'] ?? null;

        // Kiểm tra ID sản phẩm
        if (!$product_id || !is_numeric($product_id)) {
            throw new Exception('ID sản phẩm không hợp lệ.');
        }

        // Lấy thông tin sản phẩm hiện tại từ cơ sở dữ liệu để kiểm tra xem sản phẩm có tồn tại không
        $check_query = "SELECT * FROM products WHERE id = $product_id";
        $check_result = mysqli_query($conn, $check_query);
        if (mysqli_num_rows($check_result) === 0) {
            throw new Exception('Sản phẩm không tồn tại.');
        }

        // Xóa sản phẩm khỏi cơ sở dữ liệu
        $delete_query = "DELETE FROM products WHERE id = $product_id";
        if (!mysqli_query($conn, $delete_query)) {
            throw new Exception('Lỗi khi xóa sản phẩm.');
        }

        $response = ['status' => 'success', 'message' => 'Sản phẩm đã được xóa thành công.'];
    } catch (Exception $e) {
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }

    echo json_encode($response);
    exit;
}
