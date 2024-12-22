<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="./StyleSheets/main.css">
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
    <div class="container" style="width: 1140px; margin: auto ;">
        <div class="search-bar">
            <input class="p-2 border border-gray-300 rounded w-full my-4" type="text" id="searchText" placeholder="Tìm kiếm sản phẩm..." />
        </div>
        <div id="product-list" class="grid grid-cols-5 gap-4"></div>
        <div id="pagination" class="flex justify-center mt-4"></div>
    </div>


    <?php
    include('footer.php');
    ?>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
<script>
    $(document).ready(function() {
        const categoryId = getQueryParam('id'); // Lấy categoryId từ URL
        const productsPerPage = 10;
        let currentPage = 1;
        let products = [];

        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        function fetchProducts() {
            $.ajax({
                url: 'getProductByCategory.php',
                method: 'GET',
                data: {
                    categoryId
                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        products = response.products.map(product => {
                            const images = product.image.split(','); // Tách chuỗi hình ảnh thành mảng
                            return {
                                ...product,
                                images
                            };
                        });
                        renderProducts();
                        renderPagination();
                    } else {
                        $('#product-list').html('<p>Không tìm thấy sản phẩm nào.</p>');
                    }
                },
                error: function() {
                    $('#product-list').html('<p>Không thể tải sản phẩm.</p>');
                }
            });
        }

        function renderProducts() {
            const start = (currentPage - 1) * productsPerPage;
            const end = start + productsPerPage;
            const filteredProducts = products.slice(start, end);
            const searchText = $('#searchText').val().toLowerCase();

            const html = filteredProducts
                .filter(product => product.name.toLowerCase().includes(searchText))
                .map(product => `
                <div class="col-span-1 bg-white">
                    <a href="product_detail.php?productId=${product.id}&categoryId=${product.category_id}" class="cursor-pointer">
                        <div class='p-2 border rounded group h-full'>
                            <div class='text-[10px] bg-[#f1f1f1] text-[#333] rounded w-max px-3 py-[2px]'>Trả góp 0%</div>
                            <div class="py-2">
                                <img class='w-full h-44 mt-4 transition-transform duration-300 group-hover:translate-y-[-5px]' src="./admin/product_images/${product.images[0]}" alt="${product.name}" />
                            </div>
                            <div class='text-sm line-clamp-2 font-medium group-hover:text-[#2a83e9] min-h-10'>
                                ${product.name}
                            </div>
                            <div class='mt-1 text-[10px] text-gray-600 line-clamp-2'>
                                ${product.description}
                            </div>
                            <div class='mt-3 text-[#dd2f2c] text-xl font-semibold'>
                                ${parseInt(product.selling_price).toLocaleString()}₫
                            </div>
                            <div class='text-[#a4a4a4] line-through italic'>
                                ${parseInt(product.cost_price).toLocaleString()}₫
                            </div>
                            <div class='mt-2 bg-[#F1F8FE] rounded py-2 px-6 text-sm text-[#2a83e9] text-center font-semibold'>
                                Mua ngay
                            </div>
                        </div>
                    </a>
                </div>
            `)
                .join('');

            $('#product-list').html(html || '<p>Không tìm thấy sản phẩm nào phù hợp.</p>');
        }

        function renderPagination() {
            const totalPages = Math.ceil(products.length / productsPerPage);
            let html = '';

            if (totalPages > 1) {
                for (let i = 1; i <= totalPages; i++) {
                    html += `
                    <button class="pagination-btn ${i === currentPage ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700'}" data-page="${i}">
                        ${i}
                    </button>
                `;
                }
            }

            $('#pagination').html(html);
        }

        // Sự kiện tìm kiếm
        $('#searchText').on('input', function() {
            renderProducts();
            renderPagination();
        });

        // Sự kiện đổi trang
        $('#pagination').on('click', '.pagination-btn', function() {
            currentPage = parseInt($(this).data('page'));
            renderProducts();
        });

        fetchProducts();
    });
</script>

</html>