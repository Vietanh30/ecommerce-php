<?php
include('../includes/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = [];
    try {
        $product_id = $_POST['product_id'] ?? null;
        $product_title = $_POST['product_title'] ?? null;
        $cost_price = $_POST['cost_price'] ?? null;
        $selling_price = $_POST['product_price'] ?? null;
        $quantity = $_POST['quantity'] ?? null;
        $product_category = $_POST['product_category'] ?? null;
        $product_description = $_POST['product_description'] ?? null;

        if (!$product_id) {
            throw new Exception('ID sản phẩm không hợp lệ.');
        }

        $current_query = "SELECT * FROM products WHERE id = $product_id";
        $current_result = mysqli_query($conn, $current_query);
        $current_product = mysqli_fetch_assoc($current_result);

        if (!$current_product) {
            throw new Exception('Sản phẩm không tồn tại.');
        }

        $product_title = $product_title ?? $current_product['name'];
        $cost_price = $cost_price ?? $current_product['cost_price'];
        $selling_price = $selling_price ?? $current_product['selling_price'];
        $quantity = $quantity ?? $current_product['quantity'];
        $product_category = $product_category ?? $current_product['category_id'];
        $product_description = $product_description ?? $current_product['description'];

        if ($quantity < 0 || $cost_price < 0 || $selling_price < 0) {
            throw new Exception('Giá trị phải lớn hơn 0.');
        }

        if ($product_title !== $current_product['name']) {
            $check_query = "SELECT * FROM products WHERE name = '$product_title' AND id != $product_id LIMIT 1";
            $check_result = mysqli_query($conn, $check_query);
            if (mysqli_num_rows($check_result) > 0) {
                throw new Exception('Tên sản phẩm đã tồn tại trong hệ thống.');
            }
        }

        $old_images = $current_product['image'];
        $uploaded_images = [];

        if (isset($_FILES['product_images']) && $_FILES['product_images']['error'][0] !== UPLOAD_ERR_NO_FILE) {
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

        $image = !empty($uploaded_images) ? implode(',', $uploaded_images) : $old_images;

        $update_query = "UPDATE products 
                         SET name = '$product_title', 
                             cost_price = '$cost_price', 
                             selling_price = '$selling_price', 
                             quantity = '$quantity', 
                             category_id = '$product_category', 
                             description = '$product_description',
                             image = '$image' 
                         WHERE id = $product_id";

        if (!mysqli_query($conn, $update_query)) {
            throw new Exception('Lỗi khi cập nhật sản phẩm.');
        }

        $response = ['status' => 'success', 'message' => 'Cập nhật sản phẩm thành công.'];
    } catch (Exception $e) {
        $response = ['status' => 'error', 'message' => $e->getMessage()];
    }

    echo json_encode($response);
    exit;
}
