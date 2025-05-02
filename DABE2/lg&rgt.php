<?php
session_start(); // Bắt đầu session để quản lý người dùng đăng nhập
require_once './includes/database.php'; // File kết nối database

// Khởi tạo các biến thông báo
$register_error = null;
$register_success = null;
$login_error = null;


// Xử lý đăng ký
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Validate inputs
    if (empty($name) || empty($email) || empty($phone) || empty($_POST['password'])) {
        $register_error = "Vui lòng nhập đầy đủ thông tin!";
    } else {
        $db = new Database();
        $conn = Database::getConnection();

        // Kiểm tra email hoặc số điện thoại đã tồn tại chưa
        $stmt = $conn->prepare("SELECT Email, Phone FROM Users WHERE Email = ? OR Phone = ?");
        $stmt->bind_param("ss", $email, $phone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $register_error = "Email hoặc số điện thoại đã được sử dụng!";
        } else {
            $stmt = $conn->prepare("INSERT INTO Users (FullName, Email, Phone, PasswordHash, UserType) VALUES (?, ?, ?, ?, 'Regular')");
            $stmt->bind_param("ssss", $name, $email, $phone, $password);
            if ($stmt->execute()) {
                $register_success = "Đăng ký thành công!";
            } else {
                $register_error = "Đăng ký thất bại!";
            }
        }
        $stmt->close();
    }

    // Lưu thông báo vào session và chuyển hướng để tránh resubmit form
    if ($register_success) {
        $_SESSION['register_success'] = $register_success;
    } elseif ($register_error) {
        $_SESSION['register_error'] = $register_error;
    }
    header("Location: lg&rgt.php");
    exit();
}

// Xử lý đăng nhập
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $login_input = trim($_POST['login_input']);
    $password = trim($_POST['password']);

    if (empty($login_input) || empty($password)) {
        $login_error = "Vui lòng nhập đầy đủ thông tin!";
    } else {
        // Kiểm tra tài khoản admin hardcode
        if ($login_input === "admin" && $password === "admin") {
            $_SESSION['currentUser'] = [
                'name' => "Admin",
                'email' => "admin",
                'phone' => null,
                'userType' => "Admin"
            ];
            header("Location: ./admin/admin-dashboard.php");
            exit();
        }

        // Kiểm tra tài khoản trong database
        $db = new Database();
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT FullName, Email, Phone, PasswordHash, UserType FROM Users WHERE Email = ? OR Phone = ?");
        $stmt->bind_param("ss", $login_input, $login_input);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['PasswordHash'])) {
                $_SESSION['currentUser'] = [
                    'name' => $user['FullName'],
                    'email' => $user['Email'],
                    'phone' => $user['Phone'],
                    'userType' => $user['UserType']
                ];
                if ($user['UserType'] === 'Admin') {
                    header("Location: ./admin/admin-dashboard.php");
                } else {
                    header("Location: home1.php");
                }
                exit();
            } else {
                $login_error = "Sai thông tin đăng nhập hoặc mật khẩu!";
            }
        } else {
            $login_error = "Sai thông tin đăng nhập hoặc mật khẩu!";
        }
        $stmt->close();
    }

    // Lưu thông báo lỗi đăng nhập vào session và chuyển hướng
    if ($login_error) {
        $_SESSION['login_error'] = $login_error;
    }
    header("Location: lg&rgt.php");
    exit();
}

// Lấy thông báo từ session và xóa sau khi hiển thị
$register_success = isset($_SESSION['register_success']) ? $_SESSION['register_success'] : null;
$register_error = isset($_SESSION['register_error']) ? $_SESSION['register_error'] : null;
$login_error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : null;

// Xóa thông báo khỏi session sau khi lấy
unset($_SESSION['register_success']);
unset($_SESSION['register_error']);
unset($_SESSION['login_error']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link rel="stylesheet" href="public/css/styles-login.css">
    <title>Login & Register</title>
    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
        }

        .success-message {
            color: green;
            font-size: 0.9em;
        }

        .input-error {
            border: 1px solid red;
        }

        .form-container span {
            font-size: 12px;
            /* Giảm kích thước chữ */
            margin-top: 15px;
            /* Giảm khoảng cách phía trên */
        }

        .social-container a {
            width: 30px;
            /* Giảm kích thước biểu tượng */
            height: 30px;
            margin: 0 3px;
            /* Giảm khoảng cách giữa các biểu tượng */
        }

        .social-container a i {
            font-size: 14px;
            /* Giảm kích thước icon */
        }
    </style>
</head>

<body>
    <div class="container" id="container" <?php echo (isset($_SESSION['active_tab']) && $_SESSION['active_tab'] === 'register') ? 'class="right-panel-active"' : ''; ?>>
        <!-- Register Form -->
        <div class="form-container register-container">
            <form method="POST" action="lg&rgt.php" onsubmit="return validateRegister()">
                <h1>Register here.</h1>
                <?php if ($register_error): ?>
                    <span class="error-message"><?php echo $register_error; ?></span>
                <?php endif; ?>
                <?php if ($register_success): ?>
                    <span class="success-message"><?php echo $register_success; ?></span>
                <?php endif; ?>
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="phone" placeholder="Phone" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="register">Register</button>
                <span>Or use your account</span>
                <div class="social-container">
                    <a href="#" class="social"><i class="lni lni-facebook-fill"></i></a>
                    <a href="#" class="social"><i class="lni lni-google"></i></a>
                    <a href="#" class="social"><i class="lni lni-linkedin-original"></i></a>
                </div>
            </form>
        </div>

        <!-- Login Form -->
        <div class="form-container login-container">
            <form method="POST" action="lg&rgt.php" onsubmit="return validateLogin()">
                <h1>Login here.</h1>
                <?php if ($login_error): ?>
                    <span class="error-message"><?php echo $login_error; ?></span>
                <?php endif; ?>
                <input type="text" name="login_input" placeholder="Email or Phone" required>
                <input type="password" name="password" placeholder="Password" required>
                <div class="content">
                    <div class="checkbox">
                        <input type="checkbox" name="checkbox" id="checkbox">
                        <label>Remember me</label>
                    </div>
                    <div class="pass-link">
                        <a href="#">Forgot password?</a>
                    </div>
                </div>
                <button type="submit" name="login">Login</button>
                <span>Or use your account</span>
                <div class="social-container">
                    <a href="#" class="social"><i class="lni lni-facebook-fill"></i></a>
                    <a href="#" class="social"><i class="lni lni-google"></i></a>
                    <a href="#" class="social"><i class="lni lni-linkedin-original"></i></a>
                </div>
            </form>
        </div>

        <!-- Overlay -->
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1 class="title">Hello <br> Friends</h1>
                    <p>If you have an account, login here and have fun</p>
                    <button class="ghost" id="login">Login
                        <i class="lni lni-arrow-left login"></i>
                    </button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1 class="title">Start your <br> journey now</h1>
                    <p>If you don't have an account, join us and start your journey</p>
                    <button class="ghost" id="register">Register
                        <i class="lni lni-arrow-right login"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const registerButton = document.getElementById("register");
        const loginButton = document.getElementById("login");
        const container = document.getElementById("container");

        registerButton.addEventListener("click", () => {
            container.classList.add("right-panel-active");
        });

        loginButton.addEventListener("click", () => {
            container.classList.remove("right-panel-active");
        });

        function validateRegister() {
            let isValid = true;
            const inputs = document.querySelectorAll('.register-container input');
            inputs.forEach(input => {
                input.classList.remove('input-error');
                const errorSpan = input.parentElement.querySelector('.error-message') || document.createElement('span');
                errorSpan.className = 'error-message';
                errorSpan.textContent = '';
                input.parentElement.insertBefore(errorSpan, input.nextSibling);

                if (input.name === 'email') {
                    if (!input.value.includes('@') || !input.value.includes('.')) {
                        errorSpan.textContent = 'Email phải có @ và domain hợp lệ';
                        input.classList.add('input-error');
                        isValid = false;
                    }
                }

                if (input.name === 'phone') {
                    const phone = input.value;
                    if (!/^\d+$/.test(phone)) {
                        errorSpan.textContent = 'Số điện thoại chỉ được chứa số';
                        input.classList.add('input-error');
                        isValid = false;
                    } else if (phone.length !== 10) {
                        errorSpan.textContent = 'Số điện thoại phải có đúng 10 số';
                        input.classList.add('input-error');
                        isValid = false;
                    }
                }

                if (!input.value.trim()) {
                    errorSpan.textContent = `${input.placeholder} không được để trống`;
                    input.classList.add('input-error');
                    isValid = false;
                }
            });
            return isValid;
        }

        function validateLogin() {
            let isValid = true;
            const inputs = document.querySelectorAll('.login-container input');
            inputs.forEach(input => {
                input.classList.remove('input-error');
                const errorSpan = input.parentElement.querySelector('.error-message') || document.createElement('span');
                errorSpan.className = 'error-message';
                errorSpan.textContent = '';
                input.parentElement.insertBefore(errorSpan, input.nextSibling);

                if (input.name === 'login_input') {
                    if (input.value !== 'admin' && (!input.value.includes('@') || !input.value.includes('.'))) {
                        errorSpan.textContent = 'Email phải có @ và domain hợp lệ (trừ admin)';
                        input.classList.add('input-error');
                        isValid = false;
                    }
                }

                if (!input.value.trim()) {
                    errorSpan.textContent = `${input.placeholder} không được để trống`;
                    input.classList.add('input-error');
                    isValid = false;
                }
            });
            return isValid;
        }
    </script>
</body>

</html>