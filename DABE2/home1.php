<?php
session_start(); // Bắt đầu session để kiểm tra trạng thái đăng nhập
require_once 'product_database.php';

// Xử lý đăng xuất
if (isset($_GET['logout'])) {
  session_unset(); // Xóa tất cả dữ liệu session
  session_destroy(); // Hủy session
  header("Location: lg&rgt.php");
  exit();
}

$productsDB = new Product_Database();

// Xử lý tìm kiếm AJAX
if (isset($_GET['search'])) {
  $keyword = trim($_GET['search']);
  $searchResults = !empty($keyword) ? $productsDB->searchProducts($keyword) : [];
  header('Content-Type: application/json');
  echo json_encode($searchResults);
  exit();
}

// Lấy 3 sản phẩm mới nhất
$latestProducts = $productsDB->getLatestProducts(3); // Giới hạn 3 sản phẩm

$categoriesFromDB = [];
$allProducts = $productsDB->getAllProducts();
foreach ($allProducts as $product) {
  $categoryID = $product['CategoryID'];
  $sql = $productsDB->getConnection()->prepare("SELECT CategoryName FROM Categories WHERE CategoryID = ?");
  $sql->bind_param("i", $categoryID);
  $sql->execute();
  $categoryResult = $sql->get_result()->fetch_assoc();
  $normalizedCategoryName = strtolower($categoryResult['CategoryName']);

  $categoriesFromDB[$normalizedCategoryName] = $categoriesFromDB[$normalizedCategoryName] ?? [];
  $categoriesFromDB[$normalizedCategoryName][] = $product['ProductName'];
}

// Kiểm tra trạng thái đăng nhập
$isLoggedIn = isset($_SESSION['currentUser']);
$username = $isLoggedIn ? $_SESSION['currentUser']['name'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="public/css/styles-home.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin="anonymous" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous" />
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet" />
  <title>E-COMMERCE</title>
</head>

<body>
  <div class="hotline-bar">
    <span>Hotline: 0346 638 136 * Tư vấn Laptop - Điện thoại... * Địa chỉ: CS1: Quận 1 - Đồng khởi</span>
  </div>

  <header class="header" style="border-bottom: 50px solid white; background: rgb(235, 235, 235);">
    <!-- Phần còn lại của header giữ nguyên -->
    <div class="logo">
      <img src="public/img/logo.png" alt="logo" />
      <a href="">TPV E-COMMERCE</a>
    </div>
    <div class="header-slogan">
      <div class="slogan-item">
        <img src="public/img/header1.webp" alt="phone icon" class="slogan-icon" />
        <span>Chất lượng đảm bảo</span>
      </div>
      <div class="slogan-item">
        <img src="public/img/header2.webp" alt="phone icon" class="slogan-icon" />
        <span>Vận chuyển siêu tốc</span>
      </div>
      <div class="slogan-item">
        <img src="public/img/header3.webp" alt="phone icon" class="slogan-icon" />
        <span>Tư vấn Hotline: 0346638136</span>
      </div>
    </div>
    <nav>
      <a href="#">Home</a>
      <a href="#">Mac</a>
      <a href="#">Iphone</a>
      <a href="#">Watch</a>
      <a href="#">AirPods</a>
      <div class="search-container">
        <div class="search-box">
          <svg class="search-icon-input" width="20" height="20" viewBox="0 0 24 24" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <circle cx="11" cy="11" r="7" stroke="black" stroke-width="2" />
            <line x1="16.5" y1="16.5" x2="22" y2="22" stroke="black" stroke-width="2" stroke-linecap="round" />
          </svg>
          <input type="text" id="search-input" placeholder="Tìm kiếm sản phẩm" />
        </div>
        <div class="dropdown-search" id="dropdown-search">
          <p>Nhập từ khóa để tìm kiếm...</p>
        </div>
      </div>
      <?php if (!$isLoggedIn): ?>
        <a href="lg&rgt.php" id="login-btn"><button><span>Login</span></button></a>
      <?php else: ?>
        <div id="user-info">
          <span id="username"><?php echo htmlspecialchars($username); ?></span>
          <div class="user-dropdown">
            <a href="user-profile.html">Thông tin cá nhân</a>
            <a href="?logout=true" class="logout-text">Đăng xuất</a>
          </div>
        </div>
      <?php endif; ?>
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
    <div class="container" style="padding-top: 100px;">
      <div class="content" style="margin-left: -160px; width: 900px;">
        <img src="public/img/banner1.avif" alt="banner" style="border-radius: 5px; height: 400px;">
      </div>
      <div class="card-stack" style="margin-left: 300px;">
        <?php if (count($latestProducts) >= 3): ?>
          <div class="card left" data-position="left">
            <img src="public/img/<?php echo htmlspecialchars($latestProducts[0]['ImageURL']); ?>"
              alt="<?php echo htmlspecialchars($latestProducts[0]['ProductName']); ?>">
            <div class="title"><?php echo htmlspecialchars($latestProducts[0]['ProductName']); ?></div>
          </div>
          <div class="card center" data-position="center">
            <img src="public/img/<?php echo htmlspecialchars($latestProducts[1]['ImageURL']); ?>"
              alt="<?php echo htmlspecialchars($latestProducts[1]['ProductName']); ?>">
            <div class="title"><?php echo htmlspecialchars($latestProducts[1]['ProductName']); ?></div>
          </div>
          <div class="card right" data-position="right">
            <img src="public/img/<?php echo htmlspecialchars($latestProducts[2]['ImageURL']); ?>"
              alt="<?php echo htmlspecialchars($latestProducts[2]['ProductName']); ?>">
            <div class="title"><?php echo htmlspecialchars($latestProducts[2]['ProductName']); ?></div>
          </div>
        <?php else: ?>
          <p>No products available to display.</p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Section danh sách sản phẩm -->
  <section class="products-list">
    <div class="container">
      <h2 class="dssp" style="color: white">Danh Sách Sản Phẩm</h2>
      <div class="products-grid">
        <?php foreach ($allProducts as $product): ?>
          <div class="product-item">
            <div class="product-image">
              <img
                src="<?php echo !empty($product['ImageURL']) ? 'public/img/' . htmlspecialchars($product['ImageURL']) : 'public/img/placeholder.jpg'; ?>"
                alt="<?php echo htmlspecialchars($product['ProductName']); ?>">
            </div>
            <div class="product-info">
              <h3 class="product-name"><?php echo htmlspecialchars($product['ProductName']); ?></h3>
              <p class="product-price">
                <?php echo number_format($product['Price'], 0, ',', '.') . ' $'; ?>
              </p>
              <div class="product-actions">
                <button class="add-to-cart">Thêm vào giỏ hàng</button>
                <button class="buy-now">Mua ngay</button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <?php if (empty($allProducts)): ?>
          <p class="no-products">Hiện tại không có sản phẩm nào để hiển thị.</p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <script>
    const categoriesFromDB = <?php echo json_encode($categoriesFromDB); ?>;
    console.log(categoriesFromDB);

    // Cập nhật giao diện dựa trên trạng thái đăng nhập
    const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
    const username = <?php echo json_encode($username); ?>;
    const loginBtn = document.getElementById('login-btn');
    const userInfo = document.getElementById('user-info');
    const usernameSpan = document.getElementById('username');

    if (isLoggedIn) {
      if (loginBtn) loginBtn.style.display = 'none';
      if (userInfo) {
        userInfo.classList.remove('hidden');
        usernameSpan.textContent = username;
      }
    } else {
      if (loginBtn) loginBtn.style.display = 'block';
      if (userInfo) userInfo.classList.add('hidden');
    }

    // Tìm kiếm sản phẩm
    const searchInput = document.getElementById('search-input');
    const dropdownSearch = document.getElementById('dropdown-search');

    searchInput.addEventListener('input', function () {
      const keyword = this.value.trim();
      if (keyword.length > 0) {
        fetch(`home1.php?search=${encodeURIComponent(keyword)}`)
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then(searchResults => {
            console.log('Search Results:', searchResults); // Kiểm tra dữ liệu trả về
            dropdownSearch.innerHTML = '';
            if (searchResults.length > 0) {
              searchResults.forEach(product => {
                const productLink = document.createElement('a');
                productLink.href = '#';
                productLink.textContent = product.ProductName;
                dropdownSearch.appendChild(productLink);
              });
            } else {
              dropdownSearch.innerHTML = '<p>Không tìm thấy sản phẩm nào.</p>';
            }
            dropdownSearch.classList.add('active');
          })
          .catch(error => {
            console.error('Error fetching search results:', error);
            dropdownSearch.innerHTML = '<p>Có lỗi xảy ra khi tìm kiếm.</p>';
            dropdownSearch.classList.add('active');
          });
      } else {
        dropdownSearch.innerHTML = '<p>Nhập từ khóa để tìm kiếm...</p>';
        dropdownSearch.classList.remove('active');
      }
    });

    searchInput.addEventListener('focus', () => {
      if (searchInput.value.trim() === '') {
        dropdownSearch.innerHTML = '<p>Nhập từ khóa để tìm kiếm...</p>';
      }
      dropdownSearch.classList.add('active');
    });

    document.addEventListener('click', e => {
      if (!e.target.closest('.search-container')) {
        dropdownSearch.classList.remove('active');
      }
    });
  </script>
  <script src="public/js/scripts-home.js"></script>
</body>

</html>