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
  <link rel="stylesheet" href="public/css/styles-ctsp.css" />
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

  <div class="container">
        <div class="product-image">
            <img src="/api/placeholder/500/500" alt="Gaming PC with GTX 1660 Super">
        </div>
        
        <div class="product-details">
            <h1 class="product-title">PC BEST FOR GAMING i5 10400F- GTX 1660 Super 6GB(Tất cả linh kiện đều All New - bảo hành 36 tháng) - 16 slots - 8HN - 8 HCM</h1>
            
            <div class="product-status">
                <span class="status-label">Tình trạng: </span>
                <span class="out-of-stock">Hết hàng</span>
            </div>
            
            <div class="price-section">
                <div class="price-label">Giá:</div>
                <div class="price-value">10,990,000₫</div>
            </div>
            
            <div class="spec-section">
                <div class="spec-label">RAM:</div>
                <div class="spec-options">
                    <div class="option-btn selected">Ram 16GB</div>
                    <div class="option-btn">
                        Ram 32GB
                        <span class="tag">+</span>
                    </div>
                </div>
            </div>
            
            <div class="spec-section">
                <div class="spec-label">SSD:</div>
                <div class="spec-options">
                    <div class="option-btn selected">
                        SSD 256GB
                        <span class="tag">+</span>
                    </div>
                    <div class="option-btn">SSD 512GB</div>
                </div>
            </div>
            
            <div class="quantity-section">
                <div class="quantity-label">Số lượng:</div>
                <div class="quantity-control">
                    <div class="quantity-btn">-</div>
                    <input type="text" class="quantity-input" value="1">
                    <div class="quantity-btn">+</div>
                </div>
            </div>
            
            <div class="action-buttons">
                <div class="add-to-cart">THÊM VÀO GIỎ</div>
                <div class="buy-now">MUA NGAY</div>
            </div>
            
            <div class="policies">
                <div class="policy-title">Chính sách bán hàng</div>
                <div class="policy-item">
                    <div class="policy-icon">✓</div>
                    <div>Cam kết 100% chính hãng</div>
                </div>
                <div class="policy-item">
                    <div class="policy-icon">✓</div>
                    <div>Hỗ trợ 24/7</div>
                </div>
                <div class="policy-item">
                    <div class="policy-icon">✓</div>
                    <div>Hoàn tiền 111% nếu hàng giả</div>
                </div>
                <div class="policy-item">
                    <div class="policy-icon">✓</div>
                    <div>Mở hộp kiểm tra nhận hàng</div>
                </div>
                <div class="policy-item">
                    <div class="policy-icon">✓</div>
                    <div>Đổi trả trong 7 ngày</div>
                </div>
            </div>
        </div>
    </div>

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
  <script src="public/js/scripts-ctsp.js"></script>
</body>

</html>