<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      display: flex;
      height: 100vh;
      overflow: hidden;
    }

    nav {
      width: 220px;
      background-color: #2c3e50;
      color: white;
      padding-top: 30px;
      display: flex;
      flex-direction: column;
      flex-shrink: 0; /* Ngăn sidebar co lại */
    }

    nav button {
      background: none;
      border: none; 
      color: white;
      padding: 15px 20px;
      text-align: left;
      width: 100%;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }

    nav button:hover {
      background-color: #34495e;
    }

    header {
      position: absolute;
      top: 0;
      left: 220px;
      width: calc(100% - 220px);
      height: 60px;
      background-color: #ecf0f1;
      display: flex;
      align-items: center;
      padding: 0 20px;
      font-size: 20px;
      font-weight: bold;
      z-index: 10;
    }

    .content {
      position: absolute;
      top: 60px;
      left: 220px;
      width: calc(100% - 220px);
      height: calc(100% - 60px);
      overflow: auto; /* Cho phép cuộn nếu nội dung dài */
    }

    #main-frame {
      width: 100%;
      height: 100%;
      border: none;
      display: block;
    }
  </style>
</head>
<body>

  <nav>
    <button onclick="loadPage('categories-management.php')">Quản lý danh mục</button>
    <button onclick="loadPage('products-management.php')">Quản lý sản phẩm</button>
    <button onclick="loadPage('orders-management.php')">Quản lý đơn hàng</button>
    <button onclick="loadPage('users-management.php')">Quản lý người dùng</button>
  </nav>

  <header>
    Trang Admin
  </header>

  <div class="content">
    <iframe id="main-frame" src="categories-management.php"></iframe> <!-- Mặc định mở trang danh mục -->
  </div>

  <script>
    function loadPage(page) {
      document.getElementById('main-frame').src = page;
    }
  </script>

</body>
</html>