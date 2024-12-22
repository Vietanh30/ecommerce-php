<?php
include('../includes/connect.php');

$response = [];

// Kiểm tra xem ID có được gửi từ request không
if (isset($_POST['id']) && !empty($_POST['id'])) {
    $id = intval($_POST['id']); // Lấy id và chuyển thành số nguyên để đảm bảo an toàn

    // Query xóa flash sale
    $query = "DELETE FROM flashsale WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id); // Gắn tham số cho câu query
        $execute = mysqli_stmt_execute($stmt); // Thực thi query

        if ($execute) {
            $response = ['status' => 'success', 'message' => 'Flash sale đã được xóa thành công.'];
        } else {
            $response = ['status' => 'error', 'message' => 'Không thể xóa flash sale.'];
        }

        mysqli_stmt_close($stmt); // Đóng statement
    } else {
        $response = ['status' => 'error', 'message' => 'Không thể chuẩn bị truy vấn.'];
    }
} else {
    $response = ['status' => 'error', 'message' => 'ID không hợp lệ hoặc không được cung cấp.'];
}

echo json_encode($response);
exit;
