<?php
session_start();

// 如果已經登入，直接跳轉到充電頁面
if (isset($_SESSION['user_id'])) {
    header('Location: charging.php');
    exit();
}

// 處理登入錯誤訊息 (舊的 PHP 表單仍可能用得到)
$error_message = '';
if (isset($_SESSION['login_error'])) {
    $error_message = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>智慧充電樁管理平台與應用</title>
    <link rel="stylesheet" href="../css/ev-green-login.css">
    <style>
        .title {
            position: absolute;
            top: 40px;
            left: 8%;
            color: white;
            font-size: 28px;
            font-weight: bold;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }
        .register-btn {
            width: 100%;
            padding: 14px;
            background-color: #48b96c;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        .error-message {
            background: #f8d7da;
            color: #721c24;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="title">智慧充電樁管理平台與應用</div>                         
    <div class="login-wrapper">
        <div class="login-box">
            <h2>請輸入帳號與密碼</h2>
            <?php if ($error_message): ?>
                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <!-- ✅ 改用前端 JS 處理，拿掉 action -->
            <form id="loginForm" method="POST">
                <input type="email" name="email" placeholder="帳號 Email" required>
                <input type="password" name="password" placeholder="密碼 Password" required>
                <button type="submit">登入</button>
            </form>
            <button class="register-btn" onclick="window.location.href='register.php'">註冊</button>
        </div>
    </div>

    <!-- ✅ 新增：前端呼叫 API 並存 JWT -->
    <script type="module">
    import { saveToken } from '../js/auth.js';

    document.getElementById('loginForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const email    = e.target.email.value.trim();
      const password = e.target.password.value;

      const r  = await fetch('../api/auth/login.php', {
        method : 'POST',
        headers: { 'Content-Type':'application/json' },
        body   : JSON.stringify({ email, password })
      });

      const data = await r.json();
      if (r.ok) {
        // 成功：把 token 存 localStorage，導向主頁
        saveToken(data);
        location.href = 'charging.html';
      } else {
        // 失敗：顯示錯誤
        let errBox = document.querySelector('.error-message');
        if (!errBox) {
          errBox = document.createElement('div');
          errBox.className = 'error-message';
          document.getElementById('loginForm').before(errBox);
        }
        errBox.textContent = data.error ?? '登入失敗，請再試一次';
      }
    });
    </script>
</body>
</html>
