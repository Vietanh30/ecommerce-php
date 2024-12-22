<?php
include('includes/connect.php');
@session_start();

// Lấy URL hiện tại để kiểm tra active
$currentRoute = $_SERVER['REQUEST_URI'];

// Kiểm tra người dùng đã đăng nhập chưa
$isLoggedIn = isset($_SESSION['username']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<link rel="stylesheet" href="./StyleSheets/main.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<header class="bg-[#2a83e9] text-sm sticky top-0 z-50">
    <div class="container mx-auto px-4 lg:px-20">
        <div class="flex flex-wrap items-center justify-between py-3 text-white">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="index.php" class="text-lg md:text-xl font-bold text-[#fef201] whitespace-nowrap">
                    ECOMMERCE
                </a>
            </div>

            <!-- Mobile menu toggle -->
            <button class="lg:hidden p-2" id="mobile-menu-toggle">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>

            <!-- Menu -->
            <nav class="hidden lg:flex flex-col lg:flex-row w-full lg:w-auto mt-4 lg:mt-0 gap-4 lg:items-center">
                <!-- Danh mục -->


                <!-- Tìm kiếm -->
                <div class="relative w-full lg:w-72">
                    <form action="/search" method="GET" class="relative">
                        <input
                            type="text"
                            name="q"
                            placeholder="Bạn tìm gì..."
                            class="w-full lg:w-72 border rounded-[32px] py-3 px-10 focus:outline-none text-xs text-black" />
                        <button type="submit" class="absolute left-3 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-search text-gray-500"></i>
                        </button>
                    </form>
                </div>

                <!-- Giỏ hàng -->
                <a href="/cart" class="flex items-center gap-2 hover:bg-[#2871d5] rounded-[32px] py-3 px-4 whitespace-nowrap <?= $currentRoute == '/cart' ? 'bg-gray-700' : '' ?>">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Giỏ hàng</span>
                </a>

                <!-- Đăng nhập/Hiển thị tên người dùng -->
                <!-- Đăng nhập/Hiển thị tên người dùng -->
                <div class="relative" id="user-dropdown">
                    <button class="flex items-center gap-2 hover:bg-[#2871d5] rounded-[32px] py-3 px-4 whitespace-nowrap" id="user-dropdown-toggle">
                        <i class="fas fa-user"></i>
                        <span><?= $isLoggedIn ? $username : 'Đăng nhập' ?></span>
                    </button>

                    <!-- Dropdown cho đăng xuất -->
                    <div class="absolute top-full left-0 bg-white text-black shadow-lg rounded-lg z-50 w-48 hidden" id="user-dropdown-menu">
                        <?php if ($isLoggedIn): ?>
                            <a href="./logout.php" class="block px-4 py-2 hover:bg-gray-200">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </a>
                        <?php else: ?>
                            <a href="./user_login.php" class="block px-4 py-2 hover:bg-gray-200">
                                <i class="fas fa-sign-in-alt"></i> Đăng nhập
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <button class="flex items-center gap-2 bg-[#5194e8] rounded-[32px] hover:bg-[#2871d5] py-3 px-4 whitespace-nowrap min-w-44 max-w-44">
                    <i class="fas fa-map-marker-alt"></i>
                    <span class='line-clamp-1'>Hà Nội</span>
                </button>
            </nav>
        </div>
    </div>
</header>

<script>
    $(document).ready(function() {
        // Toggle dropdown menu
        $('#dropdown-toggle').on('click', function() {
            $('#dropdown-menu').toggle(); // Hiện/ẩn dropdown
        });

        // Toggle user dropdown menu
        $('#user-dropdown-toggle').on('click', function() {
            $('#user-dropdown-menu').toggle(); // Hiện/ẩn dropdown
        });

        // Đóng dropdown khi nhấp bên ngoài
        $(document).mouseup(function(e) {
            var dropdown = $("#dropdown");
            var userDropdown = $("#user-dropdown");
            if (!dropdown.is(e.target) && dropdown.has(e.target).length === 0) {
                $("#dropdown-menu").hide();
            }
            if (!userDropdown.is(e.target) && userDropdown.has(e.target).length === 0) {
                $("#user-dropdown-menu").hide();
            }
        });
    });
</script>