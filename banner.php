<!-- CDN cho Slick Slider -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css" />
<?php
// Giả sử bạn đã kết nối với cơ sở dữ liệu và lấy danh sách banner ở đây.
// Dưới đây là dữ liệu banner mẫu
$banners = [
    ['id' => 1, 'image' => './assets/home/banner1.png', 'alt' => 'Banner 1', 'link' => '#'],
    ['id' => 2, 'image' => './assets/home/banner2.png', 'alt' => 'Banner 2', 'link' => '#'],
    ['id' => 3, 'image' => './assets/home/banner3.png', 'alt' => 'Banner 3', 'link' => '#'],
    ['id' => 4, 'image' => './assets/home/banner4.png', 'alt' => 'Banner 4', 'link' => '#'],
];

// Cấu hình cho slider
$settings = json_encode([
    'dots' => false,
    'infinite' => true,
    'slidesToShow' => 1,
    'slidesToScroll' => 1,
    'arrows' => true,
    'adaptiveHeight' => true,
]);

// Nhóm banners thành cặp
$bannerPairs = [];
for ($i = 0; $i < count($banners); $i += 2) {
    $bannerPairs[] = array_slice($banners, $i, 2);
}
?>


<div class="max-w-full overflow-hidden">
    <div class="container mx-auto px-2 sm:px-4 lg:px-20 mt-5 relative">
        <div class="slider" data-settings='<?php echo $settings; ?>'>
            <?php foreach ($bannerPairs as $pair): ?>
                <div class="relative grid-cols-2 gap-4" style="display: grid !important;">
                    <div class="grid grid-cols-2 gap-4">
                        <?php foreach ($pair as $banner): ?>
                            <div class="flex items-center justify-center">
                                <a href="<?php echo $banner['link']; ?>" class="block relative overflow-hidden rounded-xl">
                                    <img
                                        src="<?php echo $banner['image']; ?>"
                                        alt="<?php echo $banner['alt']; ?>"
                                        class="w-full h-auto object-cover" />
                                </a>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- CDN cho jQuery và Slick Slider -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.js"></script>

<script>
    // Khởi tạo slider khi tài liệu đã sẵn sàng
    $(document).ready(function() {
        const sliderSettings = JSON.parse($('.slider').attr('data-settings'));
        $('.slider').slick({
            ...sliderSettings,
            nextArrow: '<button class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 z-10 w-8 h-8 md:w-10 md:h-10 flex items-center justify-center bg-white/80 hover:bg-white rounded-full shadow-md transition-all duration-200"><i class="fas fa-chevron-right text-gray-700"></i></button>',
            prevArrow: '<button class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 z-10 w-8 h-8 md:w-10 md:h-10 flex items-center justify-center bg-white/80 hover:bg-white rounded-full shadow-md transition-all duration-200"><i class="fas fa-chevron-left text-gray-700"></i></button>'
        });
    });
</script>