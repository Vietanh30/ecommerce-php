<?php
include('header.php');
include('includes/connect.php');

// Lấy productId từ URL
$productId = $_GET['productId'];
$categoryId = $_GET['categoryId'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="./StyleSheets/main.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>

<body class="min-h-screen" style="background-color: #f2f4f7;">

    <section class="container mx-auto px-4 lg:px-20 my-6">
        <div class="text-xl font-semibold">Loading...</div> <!-- Placeholder for product name -->
        <div class="mt-3">
            <div class="grid grid-cols-5 gap-4">
                <div class="col-span-3">
                    <div class='h-fit bg-white rounded-xl py-4'>
                        <div class='px-16 py-4 relative'>
                            <div class="slider">
                                <!-- Slider sẽ được cập nhật bằng AJAX -->
                            </div>
                            <button class="absolute top-1/2 -translate-y-1/2 left-4 bg-white rounded-full p-2 shadow-md hover:bg-[#8ac6ff] transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="absolute top-1/2 -translate-y-1/2 right-4 bg-white rounded-full p-2 shadow-md hover:bg-[#8ac6ff] transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        <div class='flex px-8 space-x-2'>
                            <!-- Hình ảnh thu nhỏ sẽ được cập nhật bằng AJAX -->
                        </div>
                    </div>
                    <div class='mt-5 bg-white rounded-xl px-4 pb-6 h-fit'>
                        <div class='pt-5 flex justify-center'>
                            <div class='py-2 px-16 text-[#2a83e9] bg-[#f1f8fe] rounded-lg border border-blue-200 font-semibold'>
                                Thông số kỹ thuật
                            </div>
                        </div>
                        <div class='mt-5'>
                            <!-- Thông số kỹ thuật sẽ được cập nhật bằng AJAX -->
                        </div>
                    </div>
                </div>
                <div class="col-span-2 bg-white rounded-xl px-4 pb-6 h-fit">
                    <div class='flex items-end gap-2'>
                        <div class='mt-4 text-[#dd2f2c] text-xl font-semibold discount_price'>Loading...</div> <!-- Placeholder for discount price -->
                        <div class='text-[#a4a4a4] line-through selling_price'>Loading...</div> <!-- Placeholder for cost price -->
                    </div>
                    <div class='border-b my-5'></div>
                    <div class='font-semibold'>Mô tả: <span class='text-[#344054] text-sm font-light'>Loading...</span></div> <!-- Placeholder for description -->
                    <div class='border-b my-5'></div>
                    <div class='font-semibold'>Số lượng còn lại: <span class='text-[#344054] text-sm font-light'>Loading...</span></div> <!-- Placeholder for stock -->
                    <div class='mt-5 grid grid-cols-2 gap-6'>
                        <div class='col-span-1 w-full'>
                            <div class='grid grid-cols-3 h-full'>
                                <button
                                    class="decrement-btn text-3xl px-3 py-[2px] border-black border border-r-0 col-span-1 rounded-s-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                    -
                                </button>
                                <div
                                    class='quantity-display col-span-1 w-full text-xl font-medium h-full border-black border flex items-center justify-center'
                                    data-max-quantity="1">
                                    1
                                </div>
                                <button
                                    class="increment-btn text-2xl px-3 py-[2px] border-black border border-s-0 col-span-1 rounded-e-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                                    +
                                </button>
                            </div>
                        </div>

                        <div class='border rounded flex items-center p-2 col-span-1 w-full justify-center text-[#63aaed] border-[#63aaed] cursor-pointer'>
                            <span class='mr-2 font-semibold cursor-pointer'>Thêm vào giỏ hàng</span>
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include('footer.php'); ?>

    <script>
        $(document).ready(function() {
            const productId = "<?php echo $productId; ?>";

            // Gọi AJAX để lấy thông tin chi tiết sản phẩm
            $.ajax({
                url: 'getProductFlashSaleDetail.php',
                method: 'GET',
                data: {
                    productId: productId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        const product = response.data;

                        // Cập nhật tiêu đề sản phẩm
                        $('.text-xl.font-semibold').text(product.product_name);

                        // Cập nhật slider hình ảnh
                        const slider = $('.slider');
                        const thumbnailContainer = $('.flex.px-8.space-x-2');
                        slider.empty();
                        thumbnailContainer.empty();

                        if (product.images.length > 0) {
                            // Hiển thị ảnh đầu tiên làm ảnh lớn
                            slider.append(`<img src="./admin/product_images/${product.images[0]}" id="main-image" class="w-full h-96 object-cover rounded-xl" />`);

                            // Hiển thị các ảnh nhỏ bên dưới
                            product.images.forEach((image, index) => {
                                thumbnailContainer.append(`
                                <img 
                                    src="./admin/product_images/${image}" 
                                    class="w-16 h-16 object-cover cursor-pointer rounded-md border ${index === 0 ? 'border-blue-500' : 'border-gray-200'}" 
                                    data-image="./admin/product_images/${image}" 
                                    onclick="updateMainImage(this)" 
                                />
                            `);
                            });
                        }

                        // Cập nhật giá và mô tả
                        $('.font-light').first().text(product.description); // Mô tả
                        $('.font-light').last().text(product.quantity); // Số lượng còn lại
                        $('.discount_price').text(`${number_format(parseInt(product.discount_price))}đ`);
                        $('.selling_price').text(`${number_format(parseInt(product.selling_price))}đ`);
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert("Có lỗi xảy ra khi lấy chi tiết sản phẩm.");
                }
            });

            // Hàm định dạng số
            function number_format(number) {
                return new Intl.NumberFormat('vi-VN').format(number);
            }

            // Hàm thay đổi hình ảnh lớn khi nhấn vào ảnh nhỏ
            window.updateMainImage = function(thumbnail) {
                const mainImage = $('#main-image');
                const newImage = $(thumbnail).data('image');

                // Đổi hình ảnh lớn
                mainImage.attr('src', newImage);

                // Đổi viền cho ảnh nhỏ được chọn
                $('.flex.px-8.space-x-2 img').removeClass('border-blue-500').addClass('border-gray-200');
                $(thumbnail).removeClass('border-gray-200').addClass('border-blue-500');
            };

            // Xử lý nút tăng số lượng
            $('.increment-btn').on('click', function() {
                const display = $('.quantity-display');
                let currentQuantity = parseInt(display.text());
                const max = parseInt(display.attr('data-max-quantity'));

                if (currentQuantity < max) {
                    display.text(currentQuantity + 1);
                } else {
                    Toastify({
                        text: "Số lượng vượt quá giới hạn!",
                        duration: 3000,
                        gravity: "top",
                        position: 'right',
                        backgroundColor: "#f44336",
                    }).showToast();
                }
            });

            // Xử lý nút giảm số lượng
            $('.decrement-btn').on('click', function() {
                const display = $('.quantity-display');
                let currentQuantity = parseInt(display.text());

                if (currentQuantity > 1) {
                    display.text(currentQuantity - 1);
                }
            });

            // Xử lý thêm vào giỏ hàng
            $('.cursor-pointer').on('click', function() {
                const quantity = parseInt($('.quantity-display').text());

                $.ajax({
                    url: '/addToCart.php',
                    method: 'POST',
                    data: {
                        productId: productId,
                        quantity: quantity
                    },
                    success: function(response) {
                        Toastify({
                            text: "Sản phẩm đã được thêm vào giỏ hàng!",
                            duration: 3000,
                            gravity: "top",
                            position: 'right',
                            backgroundColor: "#4CAF50",
                        }).showToast();
                    },
                    error: function() {
                        Toastify({
                            text: "Có lỗi xảy ra khi thêm sản phẩm!",
                            duration: 3000,
                            gravity: "top",
                            position: 'right',
                            backgroundColor: "#f44336",
                        }).showToast();
                    }
                });
            });
        });
    </script>


</body>

</html>