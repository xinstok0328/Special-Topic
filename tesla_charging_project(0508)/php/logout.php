<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="3;url=../html/login.php">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登出成功</title>
    <style>
        body {
            background: #e0f7ec;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Noto Sans TC', sans-serif;
        }
        .logout-box {
            background: #fff;
            padding: 40px 32px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.10);
            text-align: center;
        }
        .logout-box h2 {
            color: #48b96c;
            margin-bottom: 16px;
        }
        .logout-box p {
            color: #555;
            margin-bottom: 24px;
        }
        .logout-box a {
            display: inline-block;
            padding: 10px 28px;
            background: #48b96c;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s;
        }
        .logout-box a:hover {
            background: #3aa85b;
        }
    </style>
</head>
<body>
    <div class="logout-box">
        <h2>您已成功登出</h2>
        <p>3 秒後自動返回登入頁面。</p>
        <a href="../html/login.php">立即返回登入</a>
    </div>
</body>
</html>
