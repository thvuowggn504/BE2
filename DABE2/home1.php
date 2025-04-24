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

// Lấy 3 sản phẩm mới nhất cho card-stack
$latestProducts = $productsDB->getLatestProducts(3); // Giới hạn 3 sản phẩm

// Lấy tất cả sản phẩm cho danh sách sản phẩm
$allProducts = $productsDB->getAllProducts();

// Tạo danh sách danh mục cho dropdown
$categoriesFromDB = [];
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

// Lấy danh sách sản phẩm kèm thông tin giảm giá (nếu có)
$currentDate = date('Y-m-d H:i:s');
$productList = [];
foreach ($allProducts as $product) {
  $productID = $product['ProductID'];
  $sql = $productsDB->getConnection()->prepare(
    "SELECT DiscountPercentage 
         FROM ProductDiscounts 
         WHERE ProductID = ? AND StartDate <= ? AND EndDate >= ?"
  );
  $sql->bind_param("iss", $productID, $currentDate, $currentDate);
  $sql->execute();
  $discountResult = $sql->get_result()->fetch_assoc();

  $product['DiscountPercentage'] = $discountResult ? $discountResult['DiscountPercentage'] : null;
  $product['CurrentPrice'] = $discountResult
    ? $product['Price'] * (1 - $discountResult['DiscountPercentage'] / 100)
    : $product['Price'];
  $productList[] = $product;
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
      <div class="close-menu">
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M6 6L18 18M18 6L6 18" stroke="#000000" stroke-width="2" stroke-linecap="round"/>
        </svg>
      </div>
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
            <a href="user-profile.html">Thông tin</a>
            <a href="?logout=true" class="logout-text">Đăng xuất</a>
          </div>
        </div>
      <?php endif; ?>
    </nav>
    <div class="hamburger-menu">
      <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M4 18L20 18" stroke="#000000" stroke-width="2" stroke-linecap="round" />
        <path d="M4 12L20 12" stroke="#000000" stroke-width="2" stroke-linecap="round" />
        <path d="M4 6L20 6" stroke="#000000" stroke-width="2" stroke-linecap="round" />
      </svg>
    </div>
  </header>

  <section>
    <div class="container row-layout">
      <div class="content">
        <img src="public/img/banner1.avif" alt="banner" loading="lazy">
      </div>
      <div class="card-stack">
        <?php if (count($latestProducts) >= 3): ?>
          <div class="card left" data-position="left">
            <a href="ctsp.php?id=<?php echo $latestProducts[0]['ProductID']; ?>">
              <img src="public/img/<?php echo htmlspecialchars($latestProducts[0]['ImageURL']); ?>"
                alt="<?php echo htmlspecialchars($latestProducts[0]['ProductName']); ?>" loading="lazy">
              <div class="title"><?php echo htmlspecialchars($latestProducts[0]['ProductName']); ?></div>
            </a>
          </div>
          <div class="card center" data-position="center">
            <a href="ctsp.php?id=<?php echo $latestProducts[1]['ProductID']; ?>">
              <img src="public/img/<?php echo htmlspecialchars($latestProducts[1]['ImageURL']); ?>"
                alt="<?php echo htmlspecialchars($latestProducts[1]['ProductName']); ?>" loading="lazy">
              <div class="title"><?php echo htmlspecialchars($latestProducts[1]['ProductName']); ?></div>
            </a>
          </div>
          <div class="card right" data-position="right">
            <a href="ctsp.php?id=<?php echo $latestProducts[2]['ProductID']; ?>">
              <img src="public/img/<?php echo htmlspecialchars($latestProducts[2]['ImageURL']); ?>"
                alt="<?php echo htmlspecialchars($latestProducts[2]['ProductName']); ?>" loading="lazy">
              <div class="title"><?php echo htmlspecialchars($latestProducts[2]['ProductName']); ?></div>
            </a>
          </div>
        <?php else: ?>
          <p>No products available to display.</p>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Section danh sách sản phẩm -->
  <div class="promo-container">
    <div class="promo-title">
      <span class="icon">⭐</span>
      <span class="icon">⚡</span>
      DANH SÁCH SẢN PHẨM
      <span class="icon">🔥</span>
    </div>

    <div class="navigation-buttons">
      <button class="nav-button prev">←</button>
      <button class="nav-button next">→</button>
    </div>

    <div class="products-slider">
      <?php if (!empty($productList)): ?>
        <?php foreach ($productList as $product): ?>
          <div class="product-card">
            <div class="product-image">
              <a href="ctsp.php?id=<?php echo $product['ProductID']; ?>">
                <img src="public/img/<?php echo htmlspecialchars($product['ImageURL']); ?>"
                  alt="<?php echo htmlspecialchars($product['ProductName']); ?>" loading="lazy">
              </a>
            </div>
            <div class="product-details">
              <div class="product-title">
                <a href="ctsp.php?id=<?php echo $product['ProductID']; ?>">
                  <?php echo htmlspecialchars($product['ProductName']); ?>
                </a>
              </div>
              <div class="price-container">
                <span class="current-price"><?php echo number_format($product['CurrentPrice'], 0); ?>₫</span>
                <?php if ($product['DiscountPercentage']): ?>
                  <div>
                    <span class="original-price"><?php echo number_format($product['Price'], 0); ?>₫</span>
                    <span class="discount-badge">-<?php echo number_format($product['DiscountPercentage'], 0); ?>%</span>
                  </div>
                <?php endif; ?>
              </div>
              <button class="add-to-cart" data-product-id="<?php echo $product['ProductID']; ?>">
                <span class="cart-icon">+</span>
                THÊM VÀO GIỎ
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Không có sản phẩm nào để hiển thị.</p>
      <?php endif; ?>
    </div>

    <div class="view-all">
      <button class="view-all-button">
        <span>✦</span> DANH SÁCH SẢN PHẨM <span>🔥</span>
      </button>
    </div>
  </div>

  <footer class="footer">
    <div class="container">
      <div class="footer-row">
        <div class="footer-column">
          <h3>Về TÔM</h3>
          <p>Trang thương mại chính thức của TÔM E-COMMERCE. Luôn tìm kiếm những sản phẩm vì mọi người.</p>
          <div class="social-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-google-plus-g"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
          </div>
        </div>

        <div class="footer-column">
          <h3>Thông tin liên hệ</h3>
          <div class="contact-info">
            <p><i class="fas fa-map-marker-alt"></i> CS1: Đồng khởi - Quận 1</p>
            <p><i class="fas fa-phone"></i> 0246638136</p>
            <p><i class="fas fa-envelope"></i> bthvuong23@gmail.com</p>
          </div>
        </div>

        <div class="footer-column">
          <h3>Tài Khoản Ngân Hàng</h3>
          <ul>
            <li><a href="#">Tài Khoản Ngân Hàng</a></li>
            <li><a href="#">Tìm kiếm</a></li>
            <li><a href="#">Phương thức thanh toán</a></li>
          </ul>
        </div>

        <div class="footer-column">
          <h3>Chính sách</h3>
          <ul>
            <li><a href="#">Chính Sách Bảo Mật</a></li>
            <li><a href="#">Qui Định Bảo Hành</a></li>
            <li><a href="#">Chính Sách Đổi Trả</a></li>
            <li><a href="#">Điều khoản sử dụng</a></li>
            <li><a href="#">Chính sách vận chuyển & kiểm hàng</a></li>
          </ul>
        </div>
      </div>
    </div>
  </footer>

  <footer>
    <div class="footer-bottom" style="padding: 10px;">
      <p>Copyright © 2025 Bản quyền của Công ty cổ phần TÔM E-COMMERCE Việt Nam - Trụ sở: Hồ Chí Minh</p>
    </div>
  </footer>

  <!-- Font Awesome for icons -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

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
            console.log('Search Results:', searchResults);
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

    // Slider navigation
    document.addEventListener('DOMContentLoaded', function () {
      const slider = document.querySelector('.products-slider');
      const prevBtn = document.querySelector('.nav-button.prev');
      const nextBtn = document.querySelector('.nav-button.next');
      const cardWidth = 295; // Card width + gap

      prevBtn.addEventListener('click', () => {
        slider.scrollLeft -= cardWidth;
      });

      nextBtn.addEventListener('click', () => {
        slider.scrollLeft += cardWidth;
      });

      // Card-stack rotation
      const cards = document.querySelectorAll('.card-stack .card');
      let currentIndex = 0;

      function rotateCards() {
        cards.forEach((card, index) => {
          const newIndex = (index + currentIndex) % cards.length;
          card.classList.remove('left', 'center', 'right');
          if (newIndex === 0) {
            card.classList.add('center');
          } else if (newIndex === 1) {
            card.classList.add('right');
          } else {
            card.classList.add('left');
          }
        });
        currentIndex = (currentIndex + 1) % cards.length;
      }

      if (cards.length >= 3) {
        rotateCards();
        let intervalId = setInterval(rotateCards, 5000);

        const cardStack = document.querySelector('.card-stack');
        cardStack.addEventListener('mouseenter', () => clearInterval(intervalId));
        cardStack.addEventListener('mouseleave', () => {
          intervalId = setInterval(rotateCards, 5000);
        });
      }
    });
  </script>
  <script src="public/js/scripts-home.js"></script>
</body>

</html>