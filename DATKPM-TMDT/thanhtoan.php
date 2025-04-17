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

// Kiểm tra trạng thái đăng nhập
$isLoggedIn = isset($_SESSION['currentUser']);
if (!$isLoggedIn) {
  // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
  header("Location: lg&rgt.php");
  exit();
}

$username = $isLoggedIn ? $_SESSION['currentUser']['name'] : '';
$email = $isLoggedIn ? $_SESSION['currentUser']['email'] : '';
$phone = $isLoggedIn && isset($_SESSION['currentUser']['phone']) ? $_SESSION['currentUser']['phone'] : '';
$address = $isLoggedIn && isset($_SESSION['currentUser']['address']) ? $_SESSION['currentUser']['address'] : '';
$city = $isLoggedIn && isset($_SESSION['currentUser']['city']) ? $_SESSION['currentUser']['city'] : '';

// Kết nối database và lấy thông tin giỏ hàng
$productsDB = new Product_Database();

// Xử lý giỏ hàng
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Kiểm tra nếu giỏ hàng trống
if (empty($_SESSION['cart'])) {
  // Demo data nếu giỏ hàng trống
  $cart = [
    ['product_id' => 1, 'name' => 'iPhone 16 Pro Max', 'price' => 1299.00, 'quantity' => 1, 'image' => 'public/img/iphone16pm.jpg'],
    ['product_id' => 2, 'name' => 'AirPods Pro 2', 'price' => 249.00, 'quantity' => 1, 'image' => 'public/img/Airpod_pro2.jpg']
  ];
} else {
  $cart = $_SESSION['cart'];
}

// Tính tổng giá trị đơn hàng
$subtotal = 0;
foreach ($cart as $item) {
  $subtotal += $item['price'] * $item['quantity'];
}

$shippingFee = 10.00;
$tax = $subtotal * 0.1; // 10% thuế
$total = $subtotal + $shippingFee + $tax;

// Xử lý form thanh toán
$orderSuccess = false;
$orderError = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['placeOrder'])) {
  // Xác thực dữ liệu đầu vào
  $fullName = trim($_POST['fullName']);
  $phone = trim($_POST['phone']);
  $address = trim($_POST['address']);
  $city = trim($_POST['city']);
  $paymentMethod = $_POST['paymentMethod'];
  
  // Kiểm tra các trường bắt buộc
  if (empty($fullName) || empty($phone) || empty($address) || empty($city)) {
    $orderError = "Vui lòng điền đầy đủ thông tin giao hàng";
  } else {
    // Thực hiện xác thực thêm cho thông tin thanh toán dựa trên phương thức thanh toán
    $paymentValid = true;
    
    if ($paymentMethod === 'creditCard') {
      $cardNumber = preg_replace('/\D/', '', $_POST['cardNumber'] ?? '');
      $expiryDate = trim($_POST['expiryDate'] ?? '');
      $cvv = trim($_POST['cvv'] ?? '');
      
      if (strlen($cardNumber) !== 16 || empty($expiryDate) || strlen($cvv) !== 3) {
        $orderError = "Thông tin thẻ không hợp lệ";
        $paymentValid = false;
      }
    }
    
    if ($paymentValid) {
      // Lưu thông tin người dùng vào session để sử dụng lần sau
      if (!isset($_SESSION['currentUser']['address'])) {
        $_SESSION['currentUser']['address'] = $address;
      }
      if (!isset($_SESSION['currentUser']['phone'])) {
        $_SESSION['currentUser']['phone'] = $phone;
      }
      if (!isset($_SESSION['currentUser']['city'])) {
        $_SESSION['currentUser']['city'] = $city;
      }
      
      // Tạo mã đơn hàng duy nhất
      $orderID = 'ORD' . time() . rand(1000, 9999);
      
      // Lưu đơn hàng vào session (trong thực tế sẽ lưu vào database)
      $_SESSION['lastOrder'] = [
        'orderID' => $orderID,
        'total' => $total,
        'items' => $cart,
        'shipping' => [
          'name' => $fullName,
          'address' => $address,
          'city' => $city,
          'phone' => $phone
        ],
        'paymentMethod' => $paymentMethod,
        'orderDate' => date('Y-m-d H:i:s')
      ];
      
      // Xóa giỏ hàng sau khi đặt hàng thành công
      $_SESSION['cart'] = [];
      
      $orderSuccess = true;
      
      // Chuyển hướng đến trang xác nhận đơn hàng
      header("Location: order-confirmation.php");
      exit();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="public/css/styles-home.css" />
  <link rel="stylesheet" href="public/css/styles-checkout.css" />
  <title>Thanh Toán - TPV E-COMMERCE</title>
  <style>
    /* Thêm style inline để đảm bảo giao diện hiển thị đúng*/
    .container{
        
    }
    .checkout-container {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 1rem;
    }
    
    .checkout-content {
      display: flex;
      flex-wrap: wrap;
      gap: 2rem;
      margin-top: 2rem;
    }
    
    .checkout-form {
      flex: 1 1 600px;
    }
    
    .order-summary {
      flex: 1 1 300px;
      background-color:rgb(243, 161, 9);
      padding: 1.5rem;
      border-radius: 8px;
      position: sticky;
      top: 20px;
      max-height: 80vh;
      overflow-y: auto;
    }
    
    .form-section {
      margin-bottom: 2rem;
      padding: 1.5rem;
      background-color:#ff8c00;
      border-radius: 8px;
    }
    
    .form-group {
      margin-bottom: 1rem;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
    }
    
    .form-group input {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    
    .form-row {
      display: flex;
      gap: 1rem;
    }
    
    .form-row .form-group {
      flex: 1;
    }
    
    .payment-method {
      margin-bottom: 1rem;
      padding: 1rem;
      border: 1px solid #ddd;
      border-radius: 4px;
      background-color: #87ceff;
    }
    
    .payment-method label {
      font-weight: 500;
      margin-left: 0.5rem;
    }
    
    .payment-details {
      margin-top: 1rem;
      padding-top: 1rem;
      border-top: 1px solid #eee;
    }
    
    .cart-item {
      display: flex;
      margin-bottom: 1rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid #eee;
    }
    
    .item-image {
      width: 80px;
      height: 80px;
      margin-right: 1rem;
    }
    
    .item-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 4px;
    }
    
    .item-details h3 {
      margin: 0 0 0.5rem;
      font-size: 1rem;
    }
    
    .order-total-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.5rem;
    }
    
    .order-total-row.total {
      margin-top: 1rem;
      padding-top: 1rem;
      border-top: 1px solid #ddd;
      font-weight: bold;
      font-size: 1.2rem;
    }
    
    .checkout-button {
      display: block;
      width: 100%;
      padding: 1rem;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      font-size: 1.1rem;
      font-weight: 500;
      cursor: pointer;
      margin-top: 1.5rem;
    }
    
    .checkout-button:hover {
      background-color: #45a049;
    }
    
    .back-to-cart {
      display: inline-block;
      margin-top: 1rem;
      color: #666;
      text-decoration: none;
    }
    
    .back-to-cart:hover {
      text-decoration: underline;
    }
    
    .error-message {
      padding: 1rem;
      background-color: #f8d7da;
      color: #721c24;
      border-radius: 4px;
      margin-bottom: 1rem;
    }
    
    
    @media (max-width: 768px) {
      .checkout-content {
        flex-direction: column-reverse;
      }
      
      .form-row {
        flex-direction: column;
        gap: 0;
      }
    }
  </style>
</head>

<body>
  <header>
    <div class="logo" class="container">
      <img src="public/img/logo.png" alt="logo" />
      <a href="home1.php" style="color:Black;">TPV E-COMMERCE</a>
    </div>
    <nav>
      <a href="home1.php" style="color:Black;">Home</a>
      <a href="#" style="color:Black;">Mac</a>
      <a href="#" style="color:Black;">Iphone</a>
      <a href="#" style="color:Black;">Watch</a>
      <a href="#" style="color:Black;">AirPods</a>
      <?php if (!$isLoggedIn): ?>
        <a href="lg&rgt.php" id="login-btn" style="color:Black;"><button><span>Login</span></button></a>
      <?php else: ?>
        <div id="user-info">
          <span id="username"><?php echo htmlspecialchars($username); ?></span>
          <div class="user-dropdown">
            <a href="user-profile.html" style="color:Black;">Thông tin cá nhân</a>
            <a href="?logout=true" class="logout-text" style="color:Black;">Đăng xuất</a>
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

  <main class="checkout-container">
    <h1>Thanh Toán</h1>
    
    <?php if ($orderError): ?>
      <div class="error-message">
        <?php echo htmlspecialchars($orderError); ?>
      </div>
    <?php endif; ?>

    <div class="checkout-content">
      <div class="checkout-form">
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
          <div class="form-section">
            <h2 style="color:Black;">Thông Tin Giao Hàng</h2>
            <div class="form-group">
              <label for="fullName" style="color:Black;">Họ và tên</label>
              <input type="text" id="fullName" name="fullName" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            <div class="form-group">
              <label for="email" style="color:Black;">Email</label>
              <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="form-group">
              <label for="phone" style="color:Black;">Số điện thoại</label>
              <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
            </div>
            <div class="form-group">
              <label for="address" style="color:Black;">Địa chỉ</label>
              <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="city" style="color:Black;">Thành phố</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($city); ?>" required>
              </div>
              <div class="form-group">
                <label for="zipCode" style="color:Black;">Mã bưu điện</label>
                <input type="text" id="zipCode" name="zipCode">
              </div>
            </div>
          </div>

          <div class="form-section">
            <h2 style="color:Black;">Phương Thức Thanh Toán</h2>
            <div class="payment-methods">
              <div class="payment-method">
                <input type="radio" id="creditCard" name="paymentMethod" value="creditCard" checked>
                <label for="creditCard" style="color:Black;">Thẻ tín dụng</label>
                <div class="payment-details credit-card-details">
                  <div class="form-group">
                    <label for="cardNumber" style="color:Black;">Số thẻ</label>
                    <input type="text" id="cardNumber" name="cardNumber" placeholder="XXXX XXXX XXXX XXXX">
                  </div>
                  <div class="form-row">
                    <div class="form-group">
                      <label for="expiryDate" style="color:Black;">Ngày hết hạn</label>
                      <input type="text" id="expiryDate" name="expiryDate" placeholder="MM/YY">
                    </div>
                    <div class="form-group">
                      <label for="cvv" style="color:Black;">CVV</label>
                      <input type="text" id="cvv" name="cvv" placeholder="XXX">
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="payment-method">
                <input type="radio" id="paypal" name="paymentMethod" value="paypal">
                <label for="paypal" style="color:Black;">PayPal</label>
              </div>
              
              <div class="payment-method">
                <input type="radio" id="cod" name="paymentMethod" value="cod">
                <label for="cod" style="color:Black;">Thanh toán khi nhận hàng (COD)</label>
              </div>
            </div>
          </div>

          <button type="submit" name="placeOrder" class="checkout-button">Đặt hàng</button>
        </form>
      </div>

      <div class="order-summary">
        <h2>Đơn Hàng Của Bạn</h2>
        <div class="cart-items">
          <?php foreach ($cart as $item): ?>
            <div class="cart-item">
              <div class="item-image">
                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
              </div>
              <div class="item-details">
                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                <div class="item-quantity">Số lượng: <?php echo $item['quantity']; ?></div>
                <div class="item-price">$<?php echo number_format($item['price'], 2); ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        
        <div class="order-totals">
          <div class="order-total-row">
            <span>Tạm tính:</span>
            <span>$<?php echo number_format($subtotal, 2); ?></span>
          </div>
          <div class="order-total-row">
            <span>Phí vận chuyển:</span>
            <span>$<?php echo number_format($shippingFee, 2); ?></span>
          </div>
          <div class="order-total-row">
            <span>Thuế (10%):</span>
            <span>$<?php echo number_format($tax, 2); ?></span>
          </div>
          <div class="order-total-row total">
            <span>Tổng cộng:</span>
            <span>$<?php echo number_format($total, 2); ?></span>
          </div>
        </div>
        
        <a href="shopcart.php" class="back-to-cart">← Quay lại giỏ hàng</a>
      </div>
    </div>
  </main>


  <script>
    // JavaScript để xử lý các phương thức thanh toán
    document.addEventListener('DOMContentLoaded', function() {
      const paymentMethods = document.querySelectorAll('input[name="paymentMethod"]');
      const creditCardDetails = document.querySelector('.credit-card-details');
      
      paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
          if (this.value === 'creditCard') {
            creditCardDetails.style.display = 'block';
          } else {
            creditCardDetails.style.display = 'none';
          }
        });
      });
      
      // Format số thẻ tín dụng
      const cardNumberInput = document.getElementById('cardNumber');
      if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function(e) {
          let value = e.target.value.replace(/\D/g, '');
          if (value.length > 16) value = value.slice(0, 16);
          
          // Add spaces
          let formattedValue = '';
          for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0) formattedValue += ' ';
            formattedValue += value[i];
          }
          
          e.target.value = formattedValue;
        });
      }
      
      // Format expiry date
      const expiryDateInput = document.getElementById('expiryDate');
      if (expiryDateInput) {
        expiryDateInput.addEventListener('input', function(e) {
          let value = e.target.value.replace(/\D/g, '');
          if (value.length > 4) value = value.slice(0, 4);
          
          if (value.length > 2) {
            e.target.value = value.slice(0, 2) + '/' + value.slice(2);
          } else {
            e.target.value = value;
          }
        });
      }
      
      // Giới hạn CVV
      const cvvInput = document.getElementById('cvv');
      if (cvvInput) {
        cvvInput.addEventListener('input', function(e) {
          let value = e.target.value.replace(/\D/g, '');
          if (value.length > 3) value = value.slice(0, 3);
          e.target.value = value;
        });
      }
      
      // Hiển thị thông tin người dùng khi hover
      const usernameElement = document.getElementById('username');
      const userDropdown = document.querySelector('.user-dropdown');
      
      if (usernameElement && userDropdown) {
        usernameElement.addEventListener('mouseenter', function() {
          userDropdown.style.display = 'block';
        });
        
        document.getElementById('user-info').addEventListener('mouseleave', function() {
          userDropdown.style.display = 'none';
        });
      }
    });
  </script>
</body>

</html>