<?php
function getProducts()
{
    global $conn;

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
}
function getCategories()
{
    global $conn;
    $select_category_query = "SELECT * FROM `categories`";
    $select_category_result = mysqli_query($conn, $select_category_query);

    $categories = [];
    while ($categories_row_data = mysqli_fetch_assoc($select_category_result)) {
        $categories[] = [
            'id' => $categories_row_data['id'],
            'name' => $categories_row_data['name'],
            'image' => $categories_row_data['image'],
        ];
    }
    return ['status' => 'success', 'data' => $categories];
}
function viewDetails($product_id)
{
    global $conn;

    $product_id = intval($product_id); // Chuyển đổi sang số nguyên để bảo mật
    $select_product_query = "SELECT * FROM `products` WHERE id=?";

    if ($stmt = mysqli_prepare($conn, $select_product_query)) {
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);
        $select_product_result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($select_product_result) > 0) {
            $row = mysqli_fetch_assoc($select_product_result);
            $product_images_array = explode(',', $row['image']);
            $product_image_one = $product_images_array[0];

            $product_data = [
                'id' => $row['id'],
                'title' => $row['name'],
                'description' => $row['description'],
                'images' => $product_images_array,
                'main_image' => $product_image_one,
                'cost_price' => $row['cost_price'],
                'selling_price' => $row['selling_price'],
                'quantity' => $row['quantity'],
                'category_id' => $row['category_id'], // Thêm trường ID danh mục
            ];
            return ['status' => 'success', 'data' => $product_data];
        } else {
            return ['status' => 'error', 'message' => 'Sản phẩm không tồn tại.'];
        }
    }
    return ['status' => 'error', 'message' => 'Có lỗi xảy ra khi truy xuất sản phẩm.'];
}
// cart function
function cart($num_of_items = 1)
{
    if (isset($_GET['add_to_cart'])) {
        global $conn;

        $getProductId = $_GET['add_to_cart'];
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        if ($user_id == null) {
            echo "<script>
                    Toastify({
                        text: 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#FF5733',
                    }).showToast();
                    window.location.href = 'user_login.php';
                  </script>";
            return;
        }

        $select_query = "SELECT * FROM `cart` WHERE product_id = $getProductId AND user_id = '$user_id'";
        $select_result = mysqli_query($conn, $select_query);
        $num_of_rows = mysqli_num_rows($select_result);

        if ($num_of_rows > 0) {
            $update_query = "UPDATE `cart` SET amount = amount + $num_of_items WHERE product_id = $getProductId AND user_id = '$user_id'";
            mysqli_query($conn, $update_query);

            echo "<script>
                    Toastify({
                        text: 'Sản phẩm đã tồn tại trong giỏ hàng. Số lượng đã được cập nhật.',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#4CAF50',
                    }).showToast();
                  </script>";
        } else {
            $insert_query = "INSERT INTO `cart` (product_id, user_id, amount) VALUES ($getProductId, '$user_id', $num_of_items)";
            mysqli_query($conn, $insert_query);

            echo "<script>
                    Toastify({
                        text: 'Thêm vào giỏ hàng thành công.',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#4CAF50',
                    }).showToast();
                  </script>";
        }

        echo "<script>window.open('products.php', '_self');</script>";
    }
}
