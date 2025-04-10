<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 90%;
        }

        .login-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-header h2 {
            font-size: 24px;
            color: #333;
            display: inline-block;
            margin: 0 10px;
            font-weight: bold;
        }

        .login-header span {
            color: #999;
        }

        .login-form-group {
            margin-bottom: 20px;
        }

        .login-form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .login-form-group input[type="text"],
        .login-form-group input[type="password"] {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .recaptcha-text {
            font-size: 12px;
            color: #777;
            margin-bottom: 15px;
        }

        .login-button {
            background-color: #ffc107;
            color: #000;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .login-button:hover {
            background-color: #e0a800;
        }

        .login-footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #555;
        }

        .login-footer a {
            color: #007bff;
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .separator {
            color: #ccc;
            margin: 0 5px;
        }

        .error-message{
            color: red;
            font-size: 12px;
            margin-top: 5px;
            font-style: italic
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Đăng nhập</h2>
            <span class="separator">|</span>
            <h2 style="color: #ccc;">Đăng ký</h2>
        </div>
        <form class="login-form" action="{{ route('auth.login') }}" method="post">
            @csrf
            <div class="login-form-group">
                <label for="email">Vui lòng nhập email của bạn</label>
                <input type="text" id="email" name="email" value="{{ old('email') }}">
                @if ($errors->has('email'))
                    <span class="error-message">{{$errors->first('email')}}</span>
                @endif
            </div>
            <div class="login-form-group">
                <label for="password">Vui lòng nhập mật khẩu</label>
                <input type="password" id="password" name="password">
                @if ($errors->has('password'))
                    <span class="error-message">{{$errors->first('password')}}</span>
                @endif
            </div>
            <p class="recaptcha-text">This site is protected by reCAPTCHA and the Google <a href="#">Privacy Policy</a> and <a href="#">Terms of Service</a> apply .</p>
            <button type="submit" class="login-button">ĐĂNG NHẬP</button>
        </form>
        <div class="login-footer">
            <span>Bạn chưa có tài khoản ?</span> <a href="#">Đăng ký</a> <br>
            <span>Bạn quên mật khẩu ?</span> <a href="#">Quên mật khẩu ?</a>
        </div>
    </div>
</body>
</html>