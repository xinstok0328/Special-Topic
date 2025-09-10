<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>註冊新帳號</title>
    <link rel="stylesheet" href="../css/ev-green-login.css">
    <style>
        .register-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #e0f7ec;
        }
        .register-box {
            background: #fff;
            padding: 40px 32px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.10);
            text-align: center;
            width: 350px;
        }
        .register-box h2 {
            color: #48b96c;
            margin-bottom: 24px;
        }
        .register-box input, .register-box select {
            width: 100%;
            padding: 12px;
            margin-bottom: 14px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .register-box button {
            width: 100%;
            padding: 12px;
            background-color: #48b96c;
            color: white;
            font-weight: bold;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .register-box button:hover {
            background-color: #3aa85b;
        }
        .register-box a {
            display: block;
            margin-top: 18px;
            color: #48b96c;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-wrapper">
        <div class="register-box">
            <h2>註冊新帳號</h2>
            <form method="POST" action="../php/register.php">
                <input type="text" name="name" placeholder="姓名" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="密碼" required>
                <input type="text" name="car_brand" placeholder="車輛品牌" required>
                <input type="text" name="car_model" placeholder="車型" required>
                <button type="submit">註冊</button>
            </form>
            <a href="login.php">已有帳號？返回登入</a>
        </div>
    </div>
</body>
</html> 