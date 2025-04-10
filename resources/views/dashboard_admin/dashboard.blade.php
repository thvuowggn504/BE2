<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Admin</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            display: flex;
            min-height: 100vh;
        }

        nav {
            background-color: #333;
            color: #fff;
            width: 200px;
            padding-top: 20px;
        }

        nav h2 {
            padding: 15px 20px;
            margin: 0;
            font-size: 1.2em;
            border-bottom: 1px solid #444;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        nav ul li a {
            display: block;
            padding: 15px 20px;
            text-decoration: none;
            color: #eee;
            transition: background-color 0.3s ease;
        }

        nav ul li a:hover {
            background-color: #444;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
        }

        .content h1 {
            margin-top: 0;
            color: #333;
        }

        .dashboard-section {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <nav>
        <h2>Admin Menu</h2>
        <ul>
            <li><a href="/admin/products">Quản lý sản phẩm</a></li>
            <li><a href="/admin/categories">Quản lý danh mục</a></li>
            <li><a href="/admin/orders">Quản lý đơn hàng</a></li>
            <li><a href="/admin/customers">Quản lý khách hàng</a></li>
            <li><a href="/admin/admins">Quản trị viên</a></li>
        </ul>
        <li><a href="{{route('auth.logout')}}">Logout</a></li>
    </nav>

    <div class="content">
        <h1>Chào mừng đến trang Admin!</h1>

        <!-- <section class="dashboard-section">
            <h2>Thống kê nhanh</h2>
            <p>Đây là khu vực hiển thị các thống kê nhanh về hệ thống.</p>
            </section>

        <section class="dashboard-section">
            <h2>Thông báo gần đây</h2>
            <p>Các thông báo mới nhất của hệ thống sẽ được hiển thị ở đây.</p>
            </section> -->

        <div id="main-content">
            <h3>Vui lòng chọn một mục từ menu bên trái để quản lý.</h3>
        </div>
    </div>

    <script>
        // Chức năng giả lập tải nội dung trang khi click vào nav link
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('nav ul li a');
            const mainContent = document.getElementById('main-content');

            navLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const href = this.getAttribute('href');
                    // Đây chỉ là giả lập, trong ứng dụng thực tế bạn sẽ fetch nội dung từ server
                    mainContent.innerHTML = `<h3>Bạn đang ở trang: ${href.split('/').pop().replace('-', ' ').replace(/^\w/, c => c.toUpperCase())}</h3><p>Nội dung của trang ${href.split('/').pop()} sẽ được hiển thị ở đây.</p>`;
                });
            });
        });
    </script>
</body>
</html>