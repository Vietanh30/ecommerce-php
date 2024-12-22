<?php
include('../includes/connect.php');
@session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $identifier = $_POST['identifier'];
    $password = $_POST['password'];

    // Kiểm tra xem người dùng nhập email hay số điện thoại
    $column = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

    // Truy vấn cơ sở dữ liệu
    $query = "SELECT * FROM users WHERE $column = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Kiểm tra mật khẩu
        if (password_verify($password, $user['password'])) {
            // Kiểm tra vai trò, chỉ cho phép tài khoản admin
            if ($user['role_id'] === 1) { // 1 là ID của Admin
                // Nếu mật khẩu đúng và là admin, lưu thông tin người dùng vào session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                // Trả về phản hồi thành công
                echo json_encode(['status' => 'success', 'message' => 'Đăng nhập thành công!']);
            } else {
                // Người dùng không phải là admin
                echo json_encode(['status' => 'error', 'message' => 'Bạn không có quyền truy cập.']);
            }
        } else {
            // Mật khẩu không đúng
            echo json_encode(['status' => 'error', 'message' => 'Mật khẩu không đúng.']);
        }
    } else {
        // Không tìm thấy người dùng
        echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy tài khoản với thông tin đã nhập.']);
    }
} else {
    // Phương thức không hợp lệ
    echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ.']);
}

exit; // Đảm bảo không có mã nào khác được thực thi sau khi gửi phản hồi