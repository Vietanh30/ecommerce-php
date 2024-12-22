<?php
include('./includes/connect.php');
include('./functions/common_function.php');

// Gọi hàm để lấy danh sách thể loại
$categoriesResponse = getCategories();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="./StyleSheets/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>

<body class="min-h-screen" style="background-color: #f2f4f7;">
    <?php
    include('header.php');
    ?>
    <section id="banner">
        <?php
        include('banner.php');
        ?>
    </section>
    <div class="container mx-auto px-4 lg:px-20 mt-6">
        <div class="text-2xl font-semibold">Danh mục</div>
        <div class="bg-white rounded-xl mt-5">
            <div class="grid grid-cols-6 gap-4">
                <?php
                if ($categoriesResponse['status'] === 'success') {
                    foreach ($categoriesResponse['data'] as $category) {
                ?>
                        <a href="products.php?id=<?php echo $category['id']; ?>">
                            <div class="col-span-1 py-4 hover:bg-gray-300 transition duration-150 ease-in-out cursor-pointer">
                                <img src="<?php echo $category['image']; ?>" alt="<?php echo $category['name']; ?>" class="w-20 h-auto mx-auto p-2 object-cover rounded" />
                                <div class="mt-2 text-center"><?php echo $category['name']; ?></div>
                            </div>
                        </a>
                <?php
                    }
                } else {
                    echo '<div class="col-span-6 text-center py-4 text-gray-500">Không có danh mục nào để hiển thị.</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <section id="flash-sales" class="">
        <div className="container mx-auto px-4 lg:px-20 mt-6" style="width: 1140px; margin: auto ;">
            <div className="text-2xl font-semibold mt-5" style="font-size: 18px; font-weight: 700;margin-top: 25px;">Khuyến mãi Online</div>
            <div className='bg-white rounded-b-xl' style="margin-top: 20px;">
                <div className="bg-white rounded-t-xl flex border-b-2 justify-start mt-3" style="background-color: #fff;  ">
                    <img src="./assets/home/flashSales.png" class="w-36 h-auto" alt="Flash Sales" style="background-color: #f1f8fe;padding-inline: 15px; padding-top: 10px;" />
                    </button>
                </div>
            </div>
            <div class="bg-white p-4 rounded-b-xl">
                <div class="flex justify-center">
                    <div class="bg-[#64B2FA] text-white rounded-[32px] px-28 py-[10px] flex items-center">
                        <span id="timeLeft" class="text-4xl font-bold mr-2">00:00:00</span>
                    </div>
                </div>
                <div class="grid grid-cols-5 gap-2 mt-5" id="flashSalesContainer">
                    <!-- Sản phẩm flash sale sẽ được thêm vào đây bằng AJAX -->
                </div>
                <div class="my-5 flex justify-center">
                    <div class="text-[#2a83e9] text-center font-semibold cursor-pointer w-max hover:text-blue-700">
                        Xem tất cả sản phẩm
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    include('footer.php');
    ?>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
<script>
    $(document).ready(function() {
        function number_format(number) {
            return new Intl.NumberFormat('vi-VN').format(number);
        }
        // Hàm để lấy dữ liệu flash sale

        function fetchFlashSales() {
            $.ajax({
                url: './admin/getFlashSale.php', // Đường dẫn tới file PHP
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    const flashSalesContainer = $('#flashSalesContainer');
                    flashSalesContainer.empty(); // Xóa nội dung cũ

                    data.data.forEach(product => {
                        const productHtml = `
                                <div class="col-span-1">
                                    <a href="product_flashsale_detail.php?productId=${product.product_id}&categoryId=${product.category_id}" class="cursor-pointer">                                        <div class='p-2 border rounded group h-full'>
                                            <div class='text-[10px] bg-[#f1f1f1] text-[#333] rounded w-max px-3 py-[2px]'>Trả góp 0%</div>
                                            <div class="py-2">
                                                <img class='w-full h-44 mt-4 transition-transform duration-300 group-hover:translate-y-[-5px]' src="./admin/product_images/${product.images[0]}" alt="${product.images[0]}" />
                                            </div>
                                            <div class='text-sm line-clamp-2 font-medium group-hover:text-[#2a83e9] min-h-10'>
                                                ${product.product_name}
                                            </div>
                                            <div class='mt-1 text-[10px] text-gray-600 line-clamp-2'>
                                                ${product.description}
                                            </div>
                                            <div class='mt-3 text-[#dd2f2c] text-xl font-semibold'>
                                                ${number_format(parseInt(product.discount_price))}₫
                                            </div>
                                            <div class='text-[#a4a4a4] line-through italic'>
                                                ${number_format(parseInt(product.cost_price))}₫
                                            </div>
                                            <div class='mt-2 bg-[#F1F8FE] rounded py-2 px-6 text-sm text-[#2a83e9] text-center font-semibold'>
                                                Mua ngay
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            `;
                        flashSalesContainer.append(productHtml);
                    });
                },
                error: function(error) {
                    console.error("Error fetching flash sales:", error);
                }
            });
        }

        // Gọi hàm để lấy sản phẩm flash sale
        fetchFlashSales();

        // Đếm ngược thời gian
        let timeLeft = 24 * 60 * 60; // 24 giờ tính bằng giây
        const timerElement = $('#timeLeft');

        const intervalId = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(intervalId);
                timerElement.text("00:00:00");
            } else {
                timeLeft--;
                const hours = Math.floor(timeLeft / 3600);
                const minutes = Math.floor((timeLeft % 3600) / 60);
                const seconds = timeLeft % 60;
                timerElement.text(`${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`);
            }
        }, 1000);

    });
</script>

</html>