<?php
// Kết nối tới cơ sở dữ liệu
include('../includes/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = [];
    try {
        // Lấy dữ liệu từ form
        $product_title = $_POST['product_title'];
        $cost_price = $_POST['cost_price'];
        $selling_price = $_POST['product_price'];
        $quantity = $_POST['quantity'];
        $product_category = $_POST['product_category'];
        $product_description = $_POST['product_description'];

        // Kiểm tra dữ liệu
        if (!$product_title || !$cost_price || !$selling_price || !$quantity || $product_category == 0 || !$product_description) {
            throw new Exception('Vui lòng điền đầy đủ thông tin sản phẩm.');
        }
        if ($quantity < 0 || $cost_price < 0 || $selling_price < 0) {
            throw new Exception('Giá trị phải lớn hơn 0.');
        }

        // Kiểm tra tên sản phẩm có tồn tại không
        $check_query = "SELECT * FROM products WHERE name = '$product_title' LIMIT 1";
        $check_result = mysqli_query($conn, $check_query);
        if (mysqli_num_rows($check_result) > 0) {
            throw new Exception('Tên sản phẩm đã tồn tại trong hệ thống.');
        }

        // Xử lý hình ảnh
        $uploaded_images = [];
        if (isset($_FILES['product_images'])) {
            foreach ($_FILES['product_images']['tmp_name'] as $key => $tmp_name) {
                $file_name = $_FILES['product_images']['name'][$key];
                $file_tmp = $_FILES['product_images']['tmp_name'][$key];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                $valid_extensions = ['jpg', 'jpeg', 'png', 'webp'];
                if (in_array($file_ext, $valid_extensions)) {
                    $new_file_name = uniqid() . '.' . $file_ext;
                    $destination = 'product_images/' . $new_file_name;
                    if (!move_uploaded_file($file_tmp, $destination)) {
                        throw new Exception('Lỗi khi tải lên ảnh: ' . $file_name);
                    }
                    $uploaded_images[] = $new_file_name;
                } else {
                    throw new Exception('Định dạng ảnh không hợp lệ: ' . $file_name . '. Vui lòng chọn định dạng file jpg, jpeg, png');
                }
            }
        }

        // Thêm sản phẩm vào cơ sở dữ liệu
        $image = implode(',', $uploaded_images);
        $insert_query = "INSERT INTO products (name, cost_price, selling_price, quantity, category_id, description, image) 
                         VALUES ('$product_title', '$cost_price', '$selling_price', '$quantity', '$product_category', '$product_description', '$image')";

        if (!mysqli_query($conn, $insert_query)) {
            throw new Exception('Lỗi khi thêm sản phẩm vào cơ sở dữ liệu.');
        }

        $response = ['status' => 'success', 'message' => 'Thêm sản phẩm thành công.'];
    } catch (Exception $e) {
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }

    echo json_encode($response);
    exit;
}
