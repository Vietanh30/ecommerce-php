<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="./StyleSheets/main.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>

<body class="min-h-screen bg-blue-50">
    <div class="container mx-auto px-4 lg:px-20 min-h-screen">
        <div class="grid grid-cols-2 items-center gap-12 min-h-screen">
            <div class="col-span-1 h-full flex items-center">
                <img class="w-full h-auto" src="./assets/login/bgLogin.png" alt="Register Background">
            </div>

            <div class="col-span-1">
                <div class="rounded-xl shadow p-5 bg-white">
                    <div class="text-center font-semibold text-xl">Đăng ký</div>

                    <form id="registerForm" method="POST" action="add_user.php">
                        <div class="mt-8">
                            <div class="relative">
                                <i class="fa-solid fa-user absolute top-1/2 transform -translate-y-1/2 left-3 text-gray-400"></i>
                                <input
                                    class="border border-[#ccc] appearance-none rounded-lg w-full py-3 pl-10 pr-3 focus:outline-[#63aaed]"
                                    type="text"
                                    placeholder="Tên người dùng"
                                    name="username"
                                    required />
                            </div>
                        </div>
                        <div class="mt-8">
                            <div class="relative">
                                <i class="fa-solid fa-phone absolute top-1/2 transform -translate-y-1/2 left-3 text-gray-400"></i>
                                <input
                                    class="border border-[#ccc] appearance-none rounded-lg w-full py-3 pl-10 pr-3 focus:outline-[#63aaed]"
                                    type="tel"
                                    placeholder="Số điện thoại"
                                    name="phone"
                                    required />
                            </div>
                        </div>
                        <div class="mt-8">
                            <div class="relative">
                                <i class="fa-solid fa-envelope absolute top-1/2 transform -translate-y-1/2 left-3 text-gray-400"></i>
                                <input
                                    class="border border-[#ccc] appearance-none rounded-lg w-full py-3 pl-10 pr-3 focus:outline-[#63aaed]"
                                    type="email"
                                    placeholder="Email"
                                    name="email"
                                    required />
                            </div>
                        </div>
                        <div class="mt-8">
                            <div class="relative">
                                <i class="fa-solid fa-lock absolute top-1/2 transform -translate-y-1/2 left-3 text-gray-400"></i>
                                <input
                                    class="border border-[#ccc] appearance-none rounded-lg w-full py-3 pl-10 pr-3 focus:outline-[#63aaed]"
                                    type="password"
                                    placeholder="Mật khẩu"
                                    name="password"
                                    required />
                            </div>
                        </div>
                        <div class="flex justify-center mt-6">
                            <button
                                class="bg-[#63aaed] w-full hover:bg-[#4b96dd] font-inter text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline"
                                type="submit">
                                Đăng ký
                            </button>
                        </div>
                        <div class="mt-4 text-end font-inter text-sm">
                            Đã có tài khoản?
                            <a href="./user_login.php">
                                <span class="text-[#63aaed] font-semibold hover:text-[#4b96dd] ms-1">Đăng nhập</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#registerForm').on('submit', function(e) {
                e.preventDefault(); // Ngăn chặn gửi form mặc định

                // Tạo đối tượng dữ liệu từ các trường input
                var formData = {
                    username: $('input[name="username"]').val(),
                    email: $('input[name="email"]').val(),
                    password: $('input[name="password"]').val(),
                    phone: $('input[name="phone"]').val()
                };

                $.ajax({
                    url: 'add_user.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {

                        if (response.status === 'success') {
                            showFlashMessage(response.message, 'success');
                            $('#registerForm')[0].reset();
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