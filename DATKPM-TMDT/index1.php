
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="public/css/styles.css">
    <title>Responsive Navbar</title>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg header">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="#" style="margin: -2% 0 0 10%; height: 150px;">
                    <img src="public/img/logo.png" alt="Logo" width="130px" class="rounded-pill">
                    <h1 class="ms-2"
                        style="font-size: 20px; font-family: 'Times New Roman', Times, serif; color: rgb(213, 25, 25);">
                        TPV <br> E-CEMMERCE
                    </h1>
                </a>

                <!-- Nút toggle khi thu nhỏ -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span style="font-size: 20px;">&#9776;</span>
                </button>

                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav d-flex align-items-center">
                        <li class="nav-item d-flex align-items-center" style="margin-bottom: 20px;">
                            <a class="nav-link fw-bold hover auth-link" href="register.html">Đăng ký</a>
                            <span class="mx-1">|</span>
                            <a class="nav-link fw-bold hover auth-link" href="login.html">Đăng nhập</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Menu -->
    <nav class="navbar navbar-expand-lg" style="background-color: rgb(215, 25, 32); padding: 0;">
        <div class="container">
            <!-- Thêm phần collapse vào menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link nav" href="#">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav" href="#">Giỏ hàng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav" href="#">Bàn phím</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav" href="#">Máy tính</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav" href="#">Tai nghe</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav" href="#">Phụ kiện</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="search-section py-4 d-none d-sm-block" style="background-image: url('public/img/');">
        <div class="container">
            <div class="row align-items-center">
                <!-- Phần văn bản -->
                <div class="col-md-6 text-danger fw-bold">
                    <h4>6 ĐIỀU BÁC HỒ DẠY CÔNG AN NHÂN DÂN</h4>
                    <p>Đối với tự mình, phải cần, kiệm, liêm, chính</p>
                    <p>Đối với đồng sự, phải thân ái giúp đỡ</p>
                    <p>Đối với Chính phủ, phải tuyệt đối trung thành</p>
                    <p>Đối với nhân dân, phải kính trọng, lễ phép</p>
                    <p>Đối với công việc, phải tận tụy</p>
                    <p>Đối với địch, phải cương quyết, khôn khéo.</p>
                </div>

                <div class="col-md-6 d-none d-md-flex justify-content-end">
                    <div class="search-box d-flex align-items-center">
                        <input type="text" class="form-control me-2" placeholder="Nhập từ khoá tìm kiếm">
                        <button class="btn btn-primary">Tìm kiếm nâng cao</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <p class="gt fw-bold mt-5" style="text-align: center; font-size: 32px;">Giới thiệu về sản phâm mới</p>

    <footer>
        <p class="ft text-danger fw-bold mt-2" style="font-size: 20px;">DỊCH VỤ CÔNG - TPV</p>
        <img src="public/img/logo.png" alt="logo" width="200px" style="margin-top: -45px;">
        <p style="margin-top: -45px;">Địa chỉ: ##########</p>
        <p style="margin-top: -15px;">Email:##########</p>
        <div class="link" style="background-color: rgb(215, 25, 32); color: white;"> 
            TPV E-COMMERCE<br>
        </div>
    </footer>

</body>

</html>