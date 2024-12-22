<?php
include('../includes/connect.php');
include('../functions/common_function.php');

// Gọi hàm để lấy danh sách thể loại
$categoriesResponse = getCategories();

// Thêm mã để lấy chi tiết sản phẩm nếu cần
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $response = viewDetails($product_id); // Gọi hàm viewDetails với product_id
}
echo json_encode($response);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <title>Quản lý sản phẩm</title>
</head>

<body>
    <?php include('./sidebar.php'); ?>

    <div class="overflow-x-auto" style="margin-left: 260px; margin-top: 60px; padding: 16px;">
        <div>
            <h2 class="font-semibold text-2xl">Quản lý sản phẩm</h2>
            <div class="mt-6 mb-4 flex items-center justify-between">
                <form id="searchForm" class="flex gap-2">
                    <select name="category" id="category" class="p-2 border border-gray-500 rounded">
                        <option value="">Tất cả thể loại</option>
                        <?php
                        if ($categoriesResponse['status'] === 'success') {
                            foreach ($categoriesResponse['data'] as $category) {
                                echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <input type="text" name="search" id="search" placeholder="Tìm kiếm sản phẩm..." class="p-2 border border-gray-500 rounded w-56">
                    <button type="submit" class="px-3 py-2 text-base rounded-md bg-blue-500 text-white">Tìm kiếm</button>
                </form>
                <button id="openModal" class="px-3 py-2 text-base rounded-md bg-blue-500 text-white hover:bg-blue-600">Thêm sản phẩm</button>
            </div>

            <table id="productTable" class="min-w-full border text-center">
                <thead class="bg-blue-500 text-white">
                    <tr>
                        <th class="px-4 py-2">STT</th>
                        <th class="px-4 py-2">Hình Ảnh</th>
                        <th class="px-4 py-2">Tên Sản Phẩm</th>
                        <th class="px-4 py-2">Giá Nhập</th>
                        <th class="px-4 py-2">Giá Bán</th>
                        <th class="px-4 py-2">Số lượng</th>
                        <th class="px-4 py-2">Hành Động</th>
                    </tr>
                </thead>
                <tbody id="productBody">
                    <tr>
                        <td colspan="6" class="text-center border px-4 py-2">Không tìm thấy sản phẩm nào.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal thêm sản phẩm -->
    <div id="addProductModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500 bg-opacity-50 hidden ">
        <div class="bg-white rounded-lg shadow-lg w-1/2 p-6 h-[90%] overflow-y-auto">
            <h2 class="text-xl font-semibold mb-4">Thêm Sản Phẩm</h2>
            <form id="addProductForm" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="productTitle" class="block text-gray-700">Tên Sản Phẩm:</label>
                    <input type="text" id="productTitle" name="product_title" required class="w-full border border-gray-400 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="costPrice" class="block text-gray-700">Giá Nhập:</label>
                    <input type="number" id="costPrice" name="cost_price" required class="w-full border border-gray-400 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="sellingPrice" class="block text-gray-700">Giá Bán:</label>
                    <input type="number" id="sellingPrice" name="product_price" required class="w-full border border-gray-400 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="quantity" class="block text-gray-700">Số Lượng:</label>
                    <input type="number" id="quantity" name="quantity" required class="w-full border border-gray-400 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="productCategory" class="block text-gray-700">Thể Loại:</label>
                    <select id="productCategory" name="product_category" required class="w-full border border-gray-400 p-2 rounded">
                        <option value="0">Chọn thể loại</option>
                        <?php
                        if ($categoriesResponse['status'] === 'success') {
                            foreach ($categoriesResponse['data'] as $category) {
                                echo "<option value='{$category['id']}'>{$category['name']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-4 md:col-span-2">
                    <label for="productDescription" class="block text-gray-700">Mô Tả:</label>
                    <textarea id="productDescription" name="product_description" required class="w-full border border-gray-400 p-2 rounded"></textarea>
                </div>
                <div class="mb-4 md:col-span-2">
                    <label for="productImages" class="block text-gray-700">Hình Ảnh:</label>
                    <input type="file" id="productImages" name="product_images[]" multiple accept="image/*" required class="w-full border border-gray-400 p-2 rounded">
                    <div id="imagePreview" class="mt-4 grid grid-cols-5 gap-4"></div> <!-- Phần xem trước ảnh -->
                </div>
                <div class="flex justify-end md:col-span-2">
                    <button type="button" id="closeModal" class="mr-2 px-4 py-2 bg-gray-300 rounded">Hủy</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Thêm Sản Phẩm</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal sửa sản phẩm -->
    <div id="editProductModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500 bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-1/2 p-6 h-[90%] overflow-y-auto">
            <h2 class="text-xl font-semibold mb-4">Sửa Sản Phẩm</h2>
            <form id="editProductForm" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" id="editProductId" name="product_id">
                <div class="mb-4">
                    <label for="editProductTitle" class="block text-gray-700">Tên Sản Phẩm:</label>
                    <input type="text" id="editProductTitle" name="product_title" required class="w-full border border-gray-400 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="editCostPrice" class="block text-gray-700">Giá Nhập:</label>
                    <input type="number" id="editCostPrice" name="cost_price" required class="w-full border border-gray-400 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="editSellingPrice" class="block text-gray-700">Giá Bán:</label>
                    <input type="number" id="editSellingPrice" name="product_price" required class="w-full border border-gray-400 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="editQuantity" class="block text-gray-700">Số Lượng:</label>
                    <input type="number" id="editQuantity" name="quantity" required class="w-full border border-gray-400 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="editProductCategory" class="block text-gray-700">Thể Loại:</label>
                    <select id="editProductCategory" name="product_category" required class="w-full border border-gray-400 p-2 rounded">
                        <option value="0">Chọn thể loại</option>
                        <?php
                        if ($categoriesResponse['status'] === 'success') {
                            foreach ($categoriesResponse['data'] as $category) {
                                echo "<option value='{$category['id']}'>{$category['name']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-4 md:col-span-2">
                    <label for="editProductDescription" class="block text-gray-700">Mô Tả:</label>
                    <textarea id="editProductDescription" name="product_description" required class="w-full border border-gray-400 p-2 rounded"></textarea>
                </div>
                <div class="mb-4 md:col-span-2">
                    <label for="editProductImages" class="block text-gray-700">Hình Ảnh Mới:</label>
                    <input type="file" id="editProductImages" name="product_images[]" multiple accept="image/*" class="w-full border border-gray-400 p-2 rounded">
                    <div id="editImagePreview" class="mt-2 grid grid-cols-5 gap-4"></div> <!-- Phần xem trước ảnh -->
                </div>
                <div class="flex justify-end md:col-span-2">
                    <button type="button" id="closeEditModal" class="mr-2 px-4 py-2 bg-gray-300 rounded">Hủy</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Cập Nhật</button>
                </div>
            </form>
        </div>
    </div>

    <div id="flashMessage" style="position: fixed; top: 20px; right: 20px; z-index: 9999; display: none;"></div>

    <script>
        function loadProducts() {
            const search = $('#search').val();
            const category = $('#category').val();

            $.ajax({
                url: 'getProducts.php',
                type: 'GET',
                data: {
                    search: search,
                    category: category
                },
                dataType: 'json',
                success: function(data) {
                    const productBody = $('#productBody');
                    productBody.empty();

                    if (data.status === 'success') {
                        data.data.forEach((product, index) => {
                            productBody.append(`
                                    <tr>
                                        <td class="border px-4 py-2">${index + 1}</td>
                                        <td class="border px-4 py-2"><img src="${product.image}" alt="${product.name}" class="w-20 h-auto mx-auto" /></td>
                                        <td class="border px-4 py-2">${product.name}</td>
                                        <td class="border px-4 py-2">${product.cost_price} VNĐ</td>
                                        <td class="border px-4 py-2">${product.selling_price} VNĐ</td>
                                        <td class="border px-4 py-2">${product.quantity}</td>
                                        <td class="border px-4 py-2">
                                            <button class="edit-button text-yellow-500 hover:underline" data-id="${product.id}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="#" class="text-red-500 hover:underline" onclick="deleteProduct(${product.id}); return false;">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>s
                                `);
                        });
                    } else {
                        productBody.append(`
                                <tr>
                                    <td colspan="6" class="text-center border px-4 py-2">${data.message}</td>
                                </tr>
                            `);
                    }
                },
                error: function() {
                    $('#productBody').empty().append(`
                            <tr>
                                <td colspan="6" class="text-center border px-4 py-2">Có lỗi xảy ra khi tải sản phẩm.</td>
                            </tr>
                        `);
                }
            });
        }

        loadProducts();

        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            loadProducts();
        });

        // Mở modal thêm sản phẩm
        $('#openModal').on('click', function() {
            $('#addProductModal').removeClass('hidden');
        });

        // Đóng modal thêm sản phẩm
        $('#closeModal').on('click', function() {
            $('#addProductModal').addClass('hidden');
        });

        // Xử lý form thêm sản phẩm
        $('#addProductForm').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                url: 'addProduct.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.status === 'success') {
                        Toastify({
                            text: response.message,
                            duration: 3000,
                            gravity: "top",
                            position: 'right',
                            backgroundColor: "#4CAF50",
                        }).showToast();
                        loadProducts(); // Tải lại danh sách sản phẩm
                        $('#addProductModal').addClass('hidden'); // Đóng modal
                        $('#addProductForm')[0].reset(); // Reset form
                        const previewContainer = $('#imagePreview');
                        previewContainer.empty(); // Xóa các ảnh xem trước trước đó
                    } else {
                        Toastify({
                            text: response.message,
                            duration: 3000,
                            gravity: "top",
                            position: 'right',
                            backgroundColor: "#f44336",
                        }).showToast();
                    }
                },
                error: function() {
                    Toastify({
                        text: "Có lỗi xảy ra, vui lòng thử lại.",
                        duration: 3000,
                        gravity: "top",
                        position: 'right',
                        backgroundColor: "#f44336",
                    }).showToast();
                }
            });
        });
        // Xem trước ảnh khi chọn
        $('#productImages').on('change', function() {
            const files = this.files;
            const previewContainer = $('#imagePreview');
            previewContainer.empty(); // Xóa các ảnh xem trước trước đó

            // Duyệt qua từng file và hiển thị
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function(e) {
                    const imgWrapper = $('<div class="relative">'); // Div wrapper cho hình ảnh và nút x
                    const img = $('<img>').attr('src', e.target.result).addClass('w-full h-auto rounded-md');
                    const removeBtn = $('<button type="button" class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1">x</button>');

                    // Thêm sự kiện xóa cho nút
                    removeBtn.on('click', function() {
                        imgWrapper.remove(); // Xóa hình ảnh khỏi danh sách
                    });

                    imgWrapper.append(img).append(removeBtn); // Thêm hình ảnh và nút x vào wrapper
                    previewContainer.append(imgWrapper); // Thêm wrapper vào preview container
                };

                reader.readAsDataURL(file);
            }
        });
        // Mở modal sửa sản phẩm
        $('#editProductImages').on('change', function() {
            const files = this.files;
            const previewContainer = $('#editImagePreview');
            previewContainer.empty(); // Xóa các ảnh xem trước trước đó

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = function(e) {
                    const imgWrapper = $('<div class="relative">'); // Div wrapper cho hình ảnh và nút x
                    const img = $('<img>').attr('src', e.target.result).addClass('w-full h-auto rounded-md');
                    const removeBtn = $('<button type="button" class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1">x</button>');

                    // Thêm sự kiện xóa cho nút
                    removeBtn.on('click', function() {
                        imgWrapper.remove(); // Xóa hình ảnh khỏi danh sách
                    });

                    imgWrapper.append(img).append(removeBtn); // Thêm hình ảnh và nút x vào wrapper
                    previewContainer.append(imgWrapper); // Thêm wrapper vào preview container
                };

                reader.readAsDataURL(file);
            }
        });
        $(document).on('click', '.edit-button', function() {
            const productId = $(this).data('id');
            $.ajax({
                url: 'getProductDetail.php', // Tạo file này để lấy thông tin sản phẩm
                type: 'GET',
                data: {
                    product_id: productId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        console.log(response)
                        const product = response.data;
                        $('#editProductId').val(product.id);
                        $('#editProductTitle').val(product.title);
                        $('#editCostPrice').val(parseInt(product.cost_price));
                        $('#editSellingPrice').val(parseInt(product.selling_price));
                        $('#editQuantity').val(product.quantity);
                        $('#editProductCategory').val(product.category_id);
                        $('#editProductDescription').val(product.description);
                        $('#editProductModal').removeClass('hidden'); // Hiện modal
                        const editImagePreview = $('#editImagePreview');
                        editImagePreview.empty();
                        product.images.forEach(imageUrl => {
                            const imgWrapper = $('<div class="relative">');
                            const img = $('<img>').attr('src', `../admin/product_images/${imageUrl}`).addClass('w-full h-auto rounded-md');
                            const removeBtn = $('<button type="button" class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1">x</button>');

                            removeBtn.on('click', function() {
                                imgWrapper.remove(); // Xóa hình ảnh khỏi danh sách
                            });

                            imgWrapper.append(img).append(removeBtn);
                            editImagePreview.append(imgWrapper);
                        });
                    } else {
                        Toastify({
                            text: response.message,
                            duration: 3000,
                            gravity: "top",
                            position: 'right',
                            backgroundColor: "#f44336",
                        }).showToast();
                    }
                },
                error: function() {
                    Toastify({
                        text: "Có lỗi xảy ra khi lấy thông tin sản phẩm.",
                        duration: 3000,
                        gravity: "top",
                        position: 'right',
                        backgroundColor: "#f44336",
                    }).showToast();
                }
            });
        });

        // Đóng modal sửa sản phẩm
        $('#closeEditModal').on('click', function() {
            $('#editProductModal').addClass('hidden');
        });

        // Xử lý form sửa sản phẩm
        $('#editProductForm').on('submit', function(e) {
            e.preventDefault();
            let productId = $('#editProductId').val(); // Lấy giá trị ID sản phẩm
            let formData = new FormData(this); // Tạo đối tượng FormData từ form

            // Thêm productId vào FormData
            formData.append('product_id', productId);
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }
            $.ajax({
                url: 'editProduct.php', // Tạo file này để cập nhật thông tin sản phẩm
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.status === 'success') {
                        Toastify({
                            text: response.message,
                            duration: 3000,
                            gravity: "top",
                            position: 'right',
                            backgroundColor: "#4CAF50",
                        }).showToast();
                        loadProducts(); // Tải lại danh sách sản phẩm
                        $('#editProductModal').addClass('hidden'); // Đóng modal
                        $('#editProductForm')[0].reset(); // Reset form
                    } else {
                        Toastify({
                            text: response.message,
                            duration: 3000,
                            gravity: "top",
                            position: 'right',
                            backgroundColor: "#f44336",
                        }).showToast();
                    }
                },
                error: function() {
                    Toastify({
                        text: "Có lỗi xảy ra, vui lòng thử lại.",
                        duration: 3000,
                        gravity: "top",
                        position: 'right',
                        backgroundColor: "#f44336",
                    }).showToast();
                }
            });
        });
        // Xóa sản phẩm

        function deleteProduct(productId) {
            if (confirm('Bạn có chắc muốn xóa sản phẩm này?')) {
                $.ajax({
                    url: 'deleteProduct.php',
                    type: 'POST',
                    data: {
                        product_id: productId
                    },
                    success: function(response) {
                        response = JSON.parse(response);
                        if (response.status === 'success') {
                            Toastify({
                                text: response.message,
                                duration: 3000,
                                gravity: "top",
                                position: 'right',
                                backgroundColor: "#4CAF50",
                            }).showToast();
                            loadProducts(); // Tải lại danh sách sản phẩm
                        } else {
                            Toastify({
                                text: response.message,
                                duration: 3000,
                                gravity: "top",
                                position: 'right',
                                backgroundColor: "#f44336",
                            }).showToast();
                        }
                    },
                    error: function() {
                        Toastify({
                            text: "Có lỗi xảy ra, vui lòng thử lại.",
                            duration: 3000,
                            gravity: "top",
                            position: 'right',
                            backgroundColor: "#f44336",
                        }).showToast();
                    }
                });
            }
        }
    </script>
</body>

</html>