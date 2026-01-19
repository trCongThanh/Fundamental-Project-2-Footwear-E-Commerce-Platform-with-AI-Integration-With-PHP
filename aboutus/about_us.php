<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;
        }

        .section-container {
            height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .section {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: absolute;
            width: 100%;
            top: 0;
            transition: transform 0.7s ease-in-out, opacity 0.7s ease-in-out;
            opacity: 0;
            background-size: cover;  /* Thêm thuộc tính này */
            background-position: center;  /* Đảm bảo ảnh được căn giữa */
        }

        .section.on-the-right { 
            transform: translateX(100%); /* Mặc định slide ra ngoài bên phải */
        }

        .section.on-the-left {
            transform: translateX(-100%);
        }

        .section.active {
            opacity: 1;
            transform: translateX(0); /* Giữ nguyên vị trí khi active */
        }

        .section.slide-in-left {
            transform: translateX(0);
            opacity: 1;
        }

        .section.slide-out-left {
            transform: translateX(-100%); /* Trượt ra bên trái */
        }

        .section.slide-in-right {
            transform: translateX(0); /* Giữ nguyên vị trí khi trượt vào */
            opacity: 1; /* Đảm bảo phần mới hiển thị */
        }

        .section.slide-out-right {
            transform: translateX(100%);
        }

        .content-box {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 8px;
            width: 75%;
        }

        .nav-buttons {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }

        .nav-button {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background-color: #bbb;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .nav-button:hover {
            background-color: #333; /* Màu nền khi hover */
            transform: scale(1.2); /* Tô đậm bằng cách phóng to nút */
        }

        .nav-button.active {
            background-color: #333; /* Màu nền cho nút đang hoạt động */
            box-shadow: 0 0 10px #fff; /* Tô đậm với bóng đổ */
        }
    </style>
</head>
<body>

    <div class="section-container">
        <?php
        $sections = [
            ['title' => 'Who Are We?', 
                'text' => 'We are a trusted distributor of top footwear brands, 
                    offering a wide selection of high-quality shoes. 
                    Committed to authenticity and style, we aim to provide a reliable and convenient shopping experience.',
                'background' => 'background1.jpg'],
            ['title' => 'What Is Our Mission?', 
                'text' => 'Our mission is to make leading shoe styles accessible to customers worldwide, 
                    by carefully selecting reputable brands that meet high standards of quality and sustainability.', 
                'background' => 'background2.jpg'],
            ['title' => 'Which Brands Do We Carry?', 'text' => 'We distribute renowned footwear brands, 
                    offering a variety of styles, from athletic shoes to fashion 
                    footwear, suitable for all ages and lifestyles.', 
                'background' => 'background3.jpg'],
            ['title' => 'Customer Commitment', 
                'text' => 'Customer satisfaction is our priority. 
                    We provide attentive support, 
                    from product advice to flexible return policies, 
                    ensuring an exceptional shopping experience.', 
                'background' => 'background4.jpg'],
        ];

        foreach ($sections as $index => $section) {
            echo "
                <div class='section " . ($index === 0 ? 'active' : '') . "' id='section-{$index}' style='background-image: url(./assets/images/{$section['background']});'>
                    <div class='content-box'>
                        <h2>{$section['title']}</h2>
                        <p>{$section['text']}</p>
                    </div>
                </div>
            ";
        }
        ?>
    </div>

    <div class="nav-buttons">
        <?php foreach ($sections as $index => $section): ?>
            <div class="nav-button <?php echo ($index === 0 ? 'active' : ''); ?>" data-index="<?php echo $index; ?>"></div>
        <?php endforeach; ?>
    </div>

    <script>
        const sections = document.querySelectorAll('.section');
        const navButtons = document.querySelectorAll('.nav-button');

        let currentIndex = 0;
        let autoSlide;
        let isTransitioning = false; // Trạng thái chuyển cảnh

        function showSection(index) {
            if ((index > currentIndex || (index === 0 && currentIndex === 3)) && !isTransitioning) {
                isTransitioning = true; // Bắt đầu chuyển cảnh, khóa chuyển đổi mới

                // Trượt ra bên trái và trượt vào từ bên phải đồng thời
                sections[currentIndex].classList.add('slide-out-left');
                sections[currentIndex].classList.remove('active');
                navButtons[currentIndex].classList.remove('active');
                navButtons[index].classList.add('active');
                
                // Hiển thị phần mới
                sections[index].classList.add('active');
                sections[index].offsetHeight; // Triggers reflow
                sections[index].classList.add('slide-in-right');

                // Đặt lại vị trí sau khi hiệu ứng kết thúc với Promise
                return new Promise(resolve => {
                    setTimeout(() => {
                        sections[currentIndex].classList.remove('slide-out-left');
                        sections[index].classList.remove('slide-in-right');
                        currentIndex = index;
                        isTransitioning = false; // Chuyển cảnh xong, cho phép chuyển tiếp khác
                        resolve();
                    }, 700); // Thời gian khớp với transition trong CSS
                });
            } else if ((index < currentIndex) && !isTransitioning) {
                isTransitioning = true; // Bắt đầu chuyển cảnh, khóa chuyển đổi mới

                // Trượt ra bên phải và trượt vào từ bên trái đồng thời
                sections[currentIndex].classList.add('slide-out-right');
                sections[currentIndex].classList.remove('active');
                navButtons[currentIndex].classList.remove('active');
                navButtons[index].classList.add('active');
                
                // Hiển thị phần mới
                sections[index].classList.add('active');
                sections[index].offsetHeight; // Triggers reflow
                sections[index].classList.add('slide-in-left');

                // Đặt lại vị trí sau khi hiệu ứng kết thúc với Promise
                return new Promise(resolve => {
                    setTimeout(() => {
                        sections[currentIndex].classList.remove('slide-out-right');
                        sections[index].classList.remove('slide-in-left');
                        currentIndex = index;
                        isTransitioning = false; // Chuyển cảnh xong, cho phép chuyển tiếp khác
                        resolve();
                    }, 700); // Thời gian khớp với transition trong CSS
                });
            }
        }

        // Tự động chuyển tiếp
        function startAutoSlide() {
            autoSlide = setInterval(() => {
                let nextIndex = (currentIndex + 1) % sections.length;
                showSection(nextIndex);
            }, 3000);
        }

        function stopAutoSlide() {
            clearInterval(autoSlide);
        }

        // Xử lý hover
        navButtons.forEach((button, index) => {
            button.addEventListener('mouseover', async () => {
                stopAutoSlide();
                index = parseInt(button.getAttribute('data-index'), 10);
                await showSection(index); // Đảm bảo hiệu ứng hoàn thành trước khi tiếp tục
            });

            button.addEventListener('mouseleave', () => {
                startAutoSlide();
            });
        });

        // Hiển thị phần đầu tiên khi trang được tải
        sections[0].classList.add('active');
        startAutoSlide();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
