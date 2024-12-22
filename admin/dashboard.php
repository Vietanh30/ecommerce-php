<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<?php
include('../includes/connect.php');

// Truy vấn dữ liệu
$category_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM categories"));
$product_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM products"));
$user_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE role_id = 2"));
$order_count = 18; // Số đơn hàng (giả sử)

$query = "SELECT c.name, COUNT(p.id) AS product_count
          FROM categories c
          LEFT JOIN products p ON c.id = p.category_id
          GROUP BY c.id
          ORDER BY product_count DESC
          LIMIT 3";
$result = mysqli_query($conn, $query);

$category_names = [];
$product_counts = [];
while ($row = mysqli_fetch_assoc($result)) {
    $category_names[] = $row['name'];
    $product_counts[] = $row['product_count'];
}

// Chuyển đổi mảng PHP thành JSON
$category_names_json = json_encode($category_names);
$product_counts_json = json_encode($product_counts);
?>

<body class="bg-gray-50">

    <div class="flex">
        <?php include('./sidebar.php'); ?>

        <div class="flex-1 p-6" style="margin-left: 260px; margin-top: 60px; padding: 16px;">
            <header class="mb-8">
                <h1 class="text-4xl font-bold text-gray-800">Dashboard</h1>
                <p class="text-gray-600 mt-2">Theo dõi và quản lý hiệu suất của cửa hàng</p>
            </header>

            <!-- Thống kê -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <h2 class="text-lg font-semibold text-gray-700">Danh mục</h2>
                    <p class="text-4xl font-bold text-blue-600"><?php echo $category_count; ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <h2 class="text-lg font-semibold text-gray-700">Sản phẩm</h2>
                    <p class="text-4xl font-bold text-green-600"><?php echo $product_count; ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <h2 class="text-lg font-semibold text-gray-700">Người dùng</h2>
                    <p class="text-4xl font-bold text-yellow-500"><?php echo $user_count; ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <h2 class="text-lg font-semibold text-gray-700">Đơn hàng</h2>
                    <p class="text-4xl font-bold text-red-500"><?php echo $order_count; ?></p>
                </div>
            </div>

            <!-- Biểu đồ -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-10">
                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Top 3 danh mục</h3>
                    <canvas id="myPieChart" class="h-64"></canvas>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Doanh thu</h3>
                    <canvas id="myAreaChart" class="h-64"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

    <script>
        // Dữ liệu
        const categoryNames = <?php echo $category_names_json; ?>;
        const productCounts = <?php echo $product_counts_json; ?>;

        document.addEventListener('DOMContentLoaded', () => {
            // Kiểm tra và hủy biểu đồ PieChart nếu đã tồn tại
            if (Chart.getChart("myPieChart")) {
                Chart.getChart("myPieChart").destroy();
            }

            const pieChartCtx = document.getElementById("myPieChart").getContext('2d');
            new Chart(pieChartCtx, {
                type: 'doughnut',
                data: {
                    labels: categoryNames,
                    datasets: [{
                        data: productCounts,
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                        hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                    }],
                },
                options: {
                    maintainAspectRatio: true, // Đặt thành true để duy trì tỷ lệ
                    responsive: true, // Đảm bảo biểu đồ có thể đáp ứng
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true
                            }
                        }
                    }
                },
            });

            // Kiểm tra và hủy biểu đồ AreaChart nếu đã tồn tại
            if (Chart.getChart("myAreaChart")) {
                Chart.getChart("myAreaChart").destroy();
            }

            const areaChartCtx = document.getElementById("myAreaChart").getContext('2d');
            new Chart(areaChartCtx, {
                type: 'line',
                data: {
                    labels: ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6"],
                    datasets: [{
                        label: "Doanh thu",
                        data: [500, 700, 800, 1000, 1500, 1800],
                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                    }],
                },
                options: {
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            ticks: {
                                beginAtZero: true
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>