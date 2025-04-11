<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="public/css/styles-ctsp.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin="anonymous" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


    <link
        href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />
    <title>Landing Page</title>
</head>

<body>
    <header>
        <div class="logo">
            <img src="public/img/logo.png" alt="logo" />
            <a href="">TPV E-COMMERCE</a>
        </div>

        <nav>
            <a href="home.html">Home</a>
            <a href="#">Mac</a>
            <a href="#">Iphone</a>
            <a href="#">Watch</a>
            <a href="#">AirPods</a>
            <div class="search-container">
                <div class="search-box">
                    <svg class="search-icon-input" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <circle cx="11" cy="11" r="7" stroke="black" stroke-width="2" />
                        <line x1="16.5" y1="16.5" x2="22" y2="22" stroke="black" stroke-width="2"
                            stroke-linecap="round" />
                    </svg>
                    <input type="text" id="search-input" placeholder="Tìm kiếm sản phẩm" />
                </div>
                <div class="dropdown-search" id="dropdown-search">
                    <p>Nhập từ khóa để tìm kiếm...</p>
                </div>
            </div>

            <a href="login.html"><button><span>Login</span></button></a>
        </nav>

        <div class="hamburger-menu">
            <svg width="35px" height="35px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 18L20 18" stroke="#ffffff" stroke-width="2" stroke-linecap="round" />
                <path d="M4 12L20 12" stroke="#ffffff" stroke-width="2" stroke-linecap="round" />
                <path d="M4 6L20 6" stroke="#ffffff" stroke-width="2" stroke-linecap="round" />
            </svg>
        </div>
    </header>

    <section>
        <div class="container">
            <div class="content">
                <h1>Chi tiết sản phẩm:</h1>
                <p>
                    Tên: Iphone 16 <br>
                    Id: ip16<br>
                    Giá: 1.024$ /<s style="opacity: .5;"><sub>1.224$</sub></s><br>
                    Mô tả: Thiết kế nhôm
                    Mặt trước Ceramic Shield thế hệ mới nhất
                    Mặt sau bằng kính pha màu (Đen, Hồng, Xanh Mòng Két, Xanh Lưu Ly)
                </p>
                <div class="container-mua">
                    <button><i class="fa-solid fa-cart-shopping"></i> Mua ngay</button>
                    <button>Thêm vào giỏ hàng</button>
                </div>
            </div>

            <div class="card-stack">
                <div class="card center" data-position="center">
                    <img src="public/img/2.gif" alt="iPhone">
                    <div class="title">iPhone Pro</div>
                </div>

            </div>
    </section>

    <script src="public/js/scripts-ctsp.js"></script>
</body>

</html>