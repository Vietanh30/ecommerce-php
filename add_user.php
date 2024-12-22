<?php
include('includes/connect.php');

header('Content-Type: application/json');
$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Nhận dữ liệu từ form
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $phone = trim($_POST['phone'] ?? '');

        // Kiểm tra dữ liệu đầu vào
        if (empty($username) || empty($email) || empty($password) || empty($phone)) {
            throw new Exception('Vui lòng điền đầy đủ tất cả các thông tin.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Địa chỉ email không hợp lệ. Vui lòng kiểm tra lại.');
        }

        if (!preg_match('/^\+?\d{10,15}$/', $phone)) {
            throw new Exception('Số điện thoại không hợp lệ. Vui lòng nhập lại.');
        }

        // Kiểm tra trùng lặp email và số điện thoại
        $stmt = $conn->prepare("SELECT id, email, phone FROM `users` WHERE email = ? OR phone = ?");
        $stmt->bind_param('ss', $email, $phone);
        $stmt->execute();
        $stmt->store_result();

        // Nếu có trùng lặp, xác định xem là email hay số điện thoại
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $existing_email, $existing_phone);
            $stmt->fetch();

            $messages = [];
            if ($existing_email === $email) {
                $messages[] = 'Email đã tồn tại.';
            }
            if ($existing_phone === $phone) {
                $messages[] = 'Số điện thoại đã tồn tại.';
            }

            throw new Exception(implode(' ', $messages));
        }

        $stmt->close();

        // Mã hóa mật khẩu
        $hash_password = password_hash($password, PASSWORD_DEFAULT);

        // Thêm người dùng
        $stmt = $conn->prepare("INSERT INTO `users` (username, email, password, phone, role_id) VALUES (?, ?, ?, ?, ?)");
        $role_id = 2; // Quyền mặc định
        $stmt->bind_param('ssssi', $username, $email, $hash_password, $phone, $role_id);

        if (!$stmt->execute()) {
            throw new Exception('Có lỗi xảy ra khi thêm người dùng. Vui lòng thử lại.');
        }

        $stmt->close();

        // Phản hồi thành công
        $response = ['status' => 'success', 'message' => 'Đăng ký tài khoản thành công.'];
    } catch (Exception $e) {
        // Phản hồi lỗi
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }
} else {
    $response = ['status' => 'error', 'message' => 'Yêu cầu không hợp lệ.'];
}

echo json_encode($response);
exit;
