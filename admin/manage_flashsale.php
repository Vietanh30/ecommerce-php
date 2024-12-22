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
    <title>Quản lý Flash Sale</title>
</head>

<body>
    <?php include('./sidebar.php'); ?>

    <div class="overflow-x-auto" style="margin-left: 260px; margin-top: 60px; padding: 16px;">
        <div>
            <h2 class="font-semibold text-2xl">Quản lý Flash Sale</h2>
            <div class="mt-6 mb-4 flex items-center justify-between">
                <form id="searchFlashSaleForm" class="flex gap-2">
                    <input type="text" name="search" id="searchFlashSale" placeholder="Tìm kiếm flash sale..." class="p-2 border border-gray-500 rounded w-56">
                    <button type="submit" class="px-3 py-2 text-base rounded-md bg-blue-500 text-white">Tìm kiếm</button>
                </form>
                <button id="openFlashSaleModal" class="px-3 py-2 text-base rounded-md bg-blue-500 text-white hover:bg-blue-600">Thêm Flash Sale</button>
            </div>

            <table id="flashSaleTable" class="min-w-full border text-center">
                <thead class="bg-blue-500 text-white">
                    <tr>
                        <th class="px-4 py-2">STT</th>
                        <th class="px-4 py-2">Hình ảnh</th>
                        <th class="px-4 py-2">Tên Sản Phẩm</th>
                        <th class="px-4 py-2">Giá Gốc</th>
                        <th class="px-4 py-2">Giá Flash Sale</th>
                        <th class="px-4 py-2">Thời Gian Bắt Đầu</th>
                        <th class="px-4 py-2">Thời Gian Kết Thúc</th>
                        <th class="px-4 py-2">Hành Động</th>
                    </tr>
                </thead>
                <tbody id="flashSaleBody">
                    <tr>
                        <td colspan="7" class="text-center border px-4 py-2">Không tìm thấy flash sale nào.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal thêm flash sale -->
    <div id="addFlashSaleModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500 bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-1/2 p-6 h-[90%] overflow-y-auto">
            <h2 class="text-xl font-semibold mb-4">Thêm Flash Sale</h2>
            <form id="addFlashSaleForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="flashSaleProduct" class="block text-gray-700">Sản Phẩm:</label>
                    <select id="flashSaleProduct" name="flash_sale_product" required class="w-full border border-gray-400 p-2 rounded">
                        <!-- Options will be populated with AJAX -->
                    </select>
                </div>
                <div class="mb-4">
                    <label for="originalPrice" class="block text-gray-700">Giá Gốc:</label>
                    <input type="text" id="originalPrice" name="original_price" required class="w-full border border-gray-400 p-2 rounded" disabled>
                </div>
                <div class="mb-4">
                    <label for="flashSalePrice" class="block text-gray-700">Giá Trị Khuyến Mãi (%):</label>
                    <input type="number" id="flashSalePrice" name="percent" required class="w-full border border-gray-400 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="startTime" class="block text-gray-700">Thời Gian Bắt Đầu:</label>
                    <input type="datetime-local" id="startTime" name="start_time" required class="w-full border border-gray-400 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="endTime" class="block text-gray-700">Thời Gian Kết Thúc:</label>
                    <input type="datetime-local" id="endTime" name="end_time" required class="w-full border border-gray-400 p-2 rounded">
                </div>
                <div class="flex justify-end md:col-span-2">
                    <button type="button" id="closeFlashSaleModal" class="mr-2 px-4 py-2 bg-gray-300 rounded">Hủy</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Thêm Flash Sale</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal sửa flash sale -->
    <div id="editFlashSaleModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-500 bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-1/2 p-6 h-[90%] overflow-y-auto">
            <h2 class="text-xl font-semibold mb-4">Sửa Flash Sale</h2>
            <form id="editFlashSaleForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" id="editFlashSaleId" name="flash_sale_id">
                <div class="mb-4">
                    <label for="editFlashSaleProduct" class="block text-gray-700">Sản Phẩm:</label>
                    <select id="editFlashSaleProduct" name="flash_sale_product" required class="w-full border border-gray-400 p-2 rounded">
                        <!-- Options will be populated with AJAX -->
                    </select>
                </div>
                <div class="mb-4">
                    <label for="editOriginalPrice" class="block text-gray-700">Giá Gốc:</label>
                    <input type="text" id="editOriginalPrice" name="original_price" required class="w-full border border-gray-400 p-2 rounded" disabled>
                </div>
                <div class="mb-4">
                    <label for="editFlashSalePrice" class="block text-gray-700">Giá Trị Khuyến Mãi (%):</label>
                    <input type="number" id="editFlashSalePrice" name="percent" required class="w-full border border-gray-400 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="editStartTime" class="block text-gray-700">Thời Gian Bắt Đầu:</label>
                    <input type="datetime-local" id="editStartTime" name="start_time" required class="w-full border border-gray-400 p-2 rounded">
                </div>
                <div class="mb-4">
                    <label for="editEndTime" class="block text-gray-700">Thời Gian Kết Thúc:</label>
                    <input type="datetime-local" id="editEndTime" name="end_time" required class="w-full border border-gray-400 p-2 rounded">
                </div>
                <div class="flex justify-end md:col-span-2">
                    <button type="button" id="closeEditFlashSaleModal" class="mr-2 px-4 py-2 bg-gray-300 rounded">Hủy</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Cập Nhật</button>
                </div>
            </form>
        </div>
    </div>

    <div id="flashMessage" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

    <script>
        // Tải danh sách flash sale vào bảng
        function loadFlashSales() {
            $.ajax({
                url: 'getFlashSale.php', // API lấy danh sách flash sale
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    if (data.status === 'success') {
                        const flashSaleBody = $('#flashSaleBody');
                        flashSaleBody.empty();
                        if (data.data.length === 0) {
                            flashSaleBody.html(`<tr>
                            <td colspan="7" class="text-center border px-4 py-2">Không tìm thấy flash sale nào.</td>
                        </tr>`);
                        } else {
                            let rows = '';
                            data.data.forEach((flashSale, index) => {
                                rows += `<tr>
                                <td class="border px-4 py-2">${index + 1}</td>
                                <td class="border px-4 py-2">
                                    <img class="w-16 h-auto mx-auto" src="../admin/product_images/${flashSale.images[0]}" alt="">
                                </td>
                                <td class="border px-4 py-2">${flashSale.product_name}</td>
                                <td class="border px-4 py-2">${parseInt(flashSale.cost_price)}</td>
                                <td class="border px-4 py-2">${flashSale.percent}%</td>
                                <td class="border px-4 py-2">${flashSale.start_time}</td>
                                <td class="border px-4 py-2">${flashSale.end_time}</td>
                                <td class="border px-4 py-2">
                                    <div class="flex gap-2">
                                        <button class="edit-btn text-yellow-500 hover:text-yellow-700" 
                                            data-id="${flashSale.id}" 
                                            data-product-id="${flashSale.product_id}" 
                                            data-price="${flashSale.percent}" 
                                            data-start="${flashSale.start_time}" 
                                            data-end="${flashSale.end_time}">
                                            <i class="fas fa-edit text-xl"></i>
                                        </button>
                                        <button class="delete-btn text-red-500 hover:text-red-700" 
                                            data-id="${flashSale.id}">
                                            <i class="fas fa-trash text-xl"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>`;
                            });
                            flashSaleBody.html(rows);
                        }
                    } else {
                        showToast('error', data.message);
                    }
                },
                error: function() {
                    console.error('Lỗi khi tải danh sách flash sale.');
                    showToast('error', 'Có lỗi xảy ra khi tải danh sách.');
                }
            });
        }

        // Xóa flash sale
        $(document).on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            if (confirm('Bạn có chắc chắn muốn xóa flash sale này?')) {
                $.ajax({
                    url: 'deleteFlashSale.php',
                    type: 'POST',
                    data: {
                        id
                    },
                    success: function(response) {
                        response = JSON.parse(response);
                        if (response.status === 'success') {
                            showToast('success', response.message);
                            loadFlashSales(); // Load lại danh sách flash sale
                        } else {
                            showToast('error', response.message);
                        }
                    },
                    error: function() {
                        showToast('error', 'Có lỗi xảy ra, vui lòng thử lại.');
                    }
                });
            }
        });

        // Mở modal sửa flash sale
        $(document).on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            const productId = $(this).data('product-id');
            const price = $(this).data('price');
            const startTime = $(this).data('start');
            const endTime = $(this).data('end');

            $('#editFlashSaleId').val(id);
            $('#editFlashSaleProduct').val(productId);
            $('#editFlashSalePrice').val(price);
            $('#editStartTime').val(startTime);
            $('#editEndTime').val(endTime);

            // Tải danh sách sản phẩm vào dropdown và lấy giá gốc
            loadProducts('#editFlashSaleProduct', productId).then(function(cost_price) {
                $('#editOriginalPrice').val(cost_price); // Hiển thị giá gốc
            });

            $('#editFlashSaleModal').removeClass('hidden');
        });

        // Khi sản phẩm được chọn
        $(document).on('change', '#editFlashSaleProduct', function() {
            const selectedOption = $(this).find('option:selected');
            const cost_price = selectedOption.data('cost_price');
            $('#editOriginalPrice').val(cost_price); // Cập nhật giá gốc
        });


        // Khi tải trang, tự động tải danh sách flash sale
        $(document).ready(function() {
            loadFlashSales();

            // Mở modal thêm flash sale
            $('#openFlashSaleModal').click(function() {
                $('#addFlashSaleModal').removeClass('hidden');
                loadProducts('#flashSaleProduct');
                clearAddFlashSaleModal(); // Làm sạch modal khi mở
            });

            $('#closeFlashSaleModal').click(function() {
                $('#addFlashSaleModal').addClass('hidden');
            });

            $('#closeEditFlashSaleModal').click(function() {
                $('#editFlashSaleModal').addClass('hidden');
            });

            // Khi sản phẩm được chọn
            $('#flashSaleProduct').change(function() {
                const selectedOption = $(this).find('option:selected');
                const cost_price = selectedOption.data('cost_price');
                $('#originalPrice').val(cost_price); // Hiển thị giá gốc
            });

            // Thêm sự kiện submit cho form thêm flash sale
            $('#addFlashSaleForm').submit(function(event) {
                event.preventDefault();
                let formData = new FormData(this);
                submitForm('addFlashSale.php', formData);
            });

            // Thêm sự kiện submit cho form sửa flash sale
            $('#editFlashSaleForm').submit(function(event) {
                event.preventDefault();
                let formData = new FormData(this);
                submitForm('editFlashSale.php', formData);
            });
        });

        // Hàm gửi form
        function submitForm(url, formData) {
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.status === 'success') {
                        showToast('success', response.message);
                        $('#flashSaleTable').load(location.href + " #flashSaleBody"); // Load lại nội dung bảng
                        // Đóng modal sau khi thêm hoặc sửa thành công
                        $('#addFlashSaleModal').addClass('hidden');
                        $('#editFlashSaleModal').addClass('hidden');
                        clearAddFlashSaleModal(); // Làm sạch modal thêm flash sale
                        loadFlashSales();
                    } else {
                        showToast('error', response.message);
                    }
                },
                error: function() {
                    showToast('error', 'Có lỗi xảy ra, vui lòng thử lại.');
                },
            });
        }

        // Hàm làm sạch modal thêm flash sale
        function clearAddFlashSaleModal() {
            $('#addFlashSaleForm')[0].reset(); // Đặt lại tất cả các trường trong form
            $('#originalPrice').val(''); // Làm sạch giá gốc nếu cần
        }

        // Hàm hiển thị thông báo
        function showToast(type, message) {
            Toastify({
                text: message,
                duration: 5000,
                gravity: "top",
                position: 'right',
                backgroundColor: type === 'success' ? 'green' : 'red',
            }).showToast();
        }

        // Tải danh sách sản phẩm vào dropdown
        function loadProducts(selectId, selectedId = null) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: 'getProducts.php', // Đảm bảo đường dẫn này đúng
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.status === 'success') {
                            let options = '<option selected disabled>Chọn sản phẩm</option>';
                            let cost_price = null;
                            data.data.forEach(function(product) {
                                options += `<option value="${product.id}" data-name="${product.name}" data-image="${product.image}" data-cost_price="${product.cost_price}" ${selectedId == product.id ? 'selected' : ''}>
                            ${product.name}
                        </option>`;
                                if (selectedId == product.id) {
                                    cost_price = product.cost_price; // Lưu giá gốc của sản phẩm đã chọn
                                }
                            });
                            $(selectId).html(options);
                            resolve(cost_price); // Trả về giá gốc
                        } else {
                            $(selectId).html('<option disabled>Không có sản phẩm nào</option>');
                            reject('Không có sản phẩm nào.');
                        }
                    },
                    error: function() {
                        console.error('Lỗi khi tải danh sách sản phẩm.');
                        $(selectId).html('<option disabled>Có lỗi xảy ra</option>');
                        reject('Có lỗi xảy ra.');
                    }
                });
            });
        }
    </script>
</body>

</html>