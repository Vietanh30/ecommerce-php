<?php
session_start();
// Giả định rằng bạn đã xác thực người dùng và có thông tin trong session
$user = $_SESSION['user'] ?? null; // Giả định thông tin người dùng được lưu trong session
$currentPage = basename($_SERVER['PHP_SELF']); // Lấy tên tệp hiện tại
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
    <title>Sidebar</title>
</head>

<body>
    <nav class="fixed top-0 z-50 w-full bg-gradient-to-r from-blue-400 to-blue-600 border-b border-blue-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <a href="dashboard.php" class="flex flex-col items-center cursor-pointer">
                        <div class="text-white text-4xl font-bold">Ecommerce</div>
                        <div class="flex gap-5 items-center">
                            <div class="w-11 border-b border-white"></div>
                            <div class="w-11 border-b border-white"></div>
                        </div>
                    </a>
                </div>
                <div class="flex items-center relative">
                    <div class="flex items-center">
                        <button type="button" class="flex text-sm bg-gray-800 rounded-full">
                            <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="user photo">
                        </button>
                        <div class="absolute z-50 my-4 text-base min-w-40 list-none bg-gray-800 hover:bg-gray-700 divide-y divide-gray-100 rounded shadow-md right-0">
                            <ul>
                                <li>
                                    <a href="admin_logout.php" class="block w-full py-2 text-sm text-center text-white shadow-md font-semibold">Đăng xuất</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-60 h-screen pt-20 transition-transform bg-blue-50 border-r border-gray-200" aria-label="Sidebar">
        <div class="h-full px-3 py-6 overflow-y-auto text-black">
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="dashboard.php" class="flex items-center p-2 rounded-lg font-semibold text-base group <?= ($currentPage === 'dashboard.php') ? 'bg-blue-500 text-white' : 'text-gray-800 hover:bg-blue-500 hover:text-white' ?>">
                        <i class="fas fa-home mr-2"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="manage_products.php" class="flex items-center p-2 rounded-lg font-semibold text-base group <?= ($currentPage === 'manage_products.php') ? 'bg-blue-500 text-white' : 'text-gray-800 hover:bg-blue-500 hover:text-white' ?>">
                        <i class="fas fa-list mr-2"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Quản lý sản phẩm</span>
                    </a>
                </li>
                <li>
                    <a href="manage_flashsale.php" class="flex items-center p-2 rounded-lg font-semibold text-base group <?= ($currentPage === 'manage_flashsale.php') ? 'bg-blue-500 text-white' : 'text-gray-800 hover:bg-blue-500 hover:text-white' ?>">
                        <i class="fas fa-calendar-check mr-2"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Quản lý Flash Sale</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <script>
        // JavaScript có thể được thêm vào nếu cần
    </script>
</body>

</html>