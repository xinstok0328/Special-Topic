<?php
session_start();
// 檢查是否已登入
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// 檢查是否為管理員
$isAdmin = isset($_SESSION['email']) && $_SESSION['email'] === 'admin@gmail.com';
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>智慧充電樁管理與應用APP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body {
            font-family: 'Noto Sans TC', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f4f8;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .banner {
            background-image: url('https://images.unsplash.com/photo-1627043577584-3b92f1c3cbb0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            height: 300px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);
        }
        .banner h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }
        .banner a {
            background-color: #00b894;
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }
        .banner a:hover {
            background-color: #019875;
        }
        .container {
            max-width: 900px;
            margin: -50px auto 0 auto;
            background: #fff;
            padding: 50px;
            border-radius: 16px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
            position: relative;
            z-index: 10;
        }
        h2 {
            color: #2c3e50;
            text-align: center;
        }
        ul {
            line-height: 1.8;
            padding-left: 20px;
        }
        li {
            margin-bottom: 15px;
        }
        li a {
            text-decoration: none;
            color: #00b894;
        }
        li a:hover {
            text-decoration: underline;
        }
        .feature-icon {
            display: inline-block;
            width: 24px;
            height: 24px;
            margin-right: 8px;
            vertical-align: middle;
        }
        .fade-in {
            animation: fadeInUp 1s;
        }
        footer {
            margin-top: auto;
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px 10px;
            font-size: 14px;
        }
        footer a {
            color: #00cec9;
            text-decoration: none;
        }
        footer a:hover {
            text-decoration: underline;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #00b894;
            color: white;
            border: none;
            border-radius: 16px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .logout-btn:hover {
            background-color: #019875;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .admin-welcome {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            font-size: 16px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body>
    <?php if ($isAdmin): ?>
    <div class="admin-welcome">歡迎管理員 <?php echo htmlspecialchars($_SESSION['name']); ?></div>
    <?php endif; ?>
    
    <a href="../php/logout.php" class="logout-btn">登出</a>
    
    <div class="banner">
        <h1>智慧充電樁管理與應用APP</h1>
        <a href="learn-more.html">立即了解更多</a>
    </div>
    <div class="container fade-in" id="features">
        <p>隨著電動車市場的蓬勃發展，現有充電樁系統普遍面臨充電站分佈不均、能源調度效率低、缺乏智慧排程與即時監控等問題。</p>
        <p>本專題旨在開發一款智慧充電樁管理與應用APP，整合物聯網（IoT）與人工智慧（AI）技術，達成以下目標：</p>
        <h2>主要功能</h2>
        <ul>
            <li>
                <a href="realtime-search.html">
                  <img src="https://cdn-icons-png.flaticon.com/512/3103/3103459.png" class="feature-icon" width="24" alt="充電查詢">
                  <strong>充電樁即時查詢：</strong> 查看附近充電站位置與狀態。
                </a>
            </li>
            <li>
                <a href="smart-schedule.html">
                  <img src="https://img.icons8.com/ios-filled/50/calendar--v1.png" class="feature-icon" alt="預約排程">
                  <strong>智慧預約排程：</strong> 根據需求與負載推薦充電時段。
                </a>
            </li>              
            <li>
                <a href="energy-management.html">
                  <img src="https://img.icons8.com/ios-filled/50/combo-chart--v1.png" class="feature-icon" alt="能源管理">
                  <strong>能源優化管理：</strong> AI智能預測與能源調度。
                </a>
            </li>
            <li>
                <a href="charging-record.html">
                  <img src="https://img.icons8.com/ios-filled/50/document--v1.png" class="feature-icon" alt="充電紀錄">
                  <strong>個人化充電紀錄與分析：</strong> 查看歷史充電數據與花費。
                </a>
            </li>
            <li>
                <a href="push-alert.html">
                  <img src="https://img.icons8.com/ios-filled/50/alarm.png" class="feature-icon" alt="推播通知">
                  <strong>推播通知與異常提醒：</strong> 即時充電進度與異常狀態提示。
                </a>
            </li>
        </ul>
        <p>透過本APP，預期能有效提升充電樁運營效率、改善用戶體驗，並促進智慧城市與綠能交通的發展。</p>
    </div>
    <footer>
        智慧充電樁管理與應用APP © 2025 | 聯絡我們：<a href="mailto:support@example.com">support@example.com</a>
    </footer>
</body>
</html>
