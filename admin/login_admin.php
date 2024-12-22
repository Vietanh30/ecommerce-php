<?php
include('../includes/connect.php');
include('../functions/common_function.php');
@session_start();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>

<body class="min-h-screen bg-blue-50">
    <div class="container mx-auto px-4 lg:px-20 min-h-screen flex items-center justify-center">
        <div class="grid grid-cols-2 items-center gap-12 min-h-screen">
            <div class="col-span-1 h-full flex items-center">
                <img class="w-full h-auto" src="../assets/login/bgLogin.png" alt="Login Background" />
            </div>

            <div class="col-span-1">
                <div class="rounded-xl shadow p-5 bg-white">
                    <div class="text-center font-semibold text-xl">Đăng Nhập</div>
                    <form id="loginForm" method="POST">
                        <div class="mt-8">
                            <div class="relative">
                                <i class="absolute top-1/2 transform -translate-y-1/2 left-3 text-gray-400 fas fa-user"></i>
                                <input
                                    class="border border-[#ccc] rounded-lg w-full py-3 pl-10 pr-3 focus:outline-[#63aaed]"
                                    type="text"
                                    placeholder="Email hoặc số điện thoại"
                                    name="identifier"
                                    required />
                            </div>
                        </div>

                        <div class="mt-8">
                            <div class="relative">
                                <i class="absolute top-1/2 transform -translate-y-1/2 left-3 text-gray-400 fas fa-lock"></i>
                                <input
                                    class="border border-[#ccc] rounded-lg w-full py-3 pl-10 pr-3 focus:outline-[#63aaed]"
                                    type="password"
                                    placeholder="Mật khẩu"
                                    name="password"
                                    required />
                            </div>
                        </div>

                        <div class="flex justify-center mt-6">
                            <button
                                class="bg-[#63aaed] w-full hover:bg-[#4b96dd] text-white font-bold py-2 px-4 rounded-lg focus:outline-none"
                                type="submit">
                                Đăng Nhập
                            </button>
                        </div>

                        <div class="mt-4 text-end">
                            <span class="text-sm">Chưa có tài khoản?</span>
                            <a href="./user_register.php" class="text-[#63aaed] font-semibold hover:text-[#4b96dd] ms-1">Đăng ký ngay</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                var formData = {
                    identifier: $('input[name="identifier"]').val(),
                    password: $('input[name="password"]').val()
                };

                $.ajax({
                    url: 'loginAdmin.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json', // Đảm bảo phản hồi được phân tích cú pháp như JSON
                    success: function(response) {
                        console.log(response); // Kiểm tra phản hồi
                        if (response.status === 'success') {
                            showFlashMessage(response.message, 'success');

                            // Chuyển hướng sau khi thông báo được hiển thị
                            setTimeout(function() {
                                window.location.href = 'dashboard.php'; // Chuyển hướng sau khi đăng nhập thành công
                            }, 2500); // Thời gian chờ (3000ms = 3 giây)
                        } else {
                            showFlashMessage(response.message, 'error');
                        }
                    },
                    error: function(error) {
                        console.error('AJAX Error:', error);
                        showFlashMessage('Đã xảy ra lỗi. Vui lòng thử lại.', 'error');
                    }
                });
            });
        });

        function showFlashMessage(message, type) {
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: 'right',
                backgroundColor: type === 'success' ? '#4CAF50' : '#FF5733',
            }).showToast();
        }
    </script>
</body>

</html>