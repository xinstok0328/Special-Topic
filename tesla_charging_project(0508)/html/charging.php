<?php
session_start();
$car_brand = isset($_SESSION['car_brand']) ? $_SESSION['car_brand'] : '';
$car_model = isset($_SESSION['car_model']) ? $_SESSION['car_model'] : '';
$car_name = trim($car_brand . ' ' . $car_model);
// 設定圖片路徑（假設圖片放在 ../img/brand_model.jpg）
$img_file = '../img/' . strtolower(str_replace(' ', '_', $car_brand . '_' . $car_model)) . '.jpg';
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>充電中</title>
    <link rel="stylesheet" href="../css/charging-style.css">
    <style>
        .logout-btn {
            position: absolute;
            top: 32px;
            right: 48px;
            padding: 12px 28px;
            background: rgba(72,185,108,0.18);
            color: #48b96c;
            border: 2px solid #48b96c;
            border-radius: 30px;
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 4px 16px rgba(72,185,108,0.08);
            backdrop-filter: blur(4px);
            transition: all 0.2s;
            text-decoration: none;
            z-index: 100;
        }
        .logout-btn:hover {
            background: #48b96c;
            color: #fff;
            border-color: #48b96c;
        }
    </style>
</head>
<body>
    <a href="../php/logout.php" class="logout-btn">登出</a>
    <div class="charging-container">
        <div class="car-name-box">
            <h2>目前充電車輛</h2>
            <?php if (file_exists($img_file)): ?>
                <img src="<?php echo $img_file; ?>" alt="<?php echo htmlspecialchars($car_name); ?>" style="max-width:180px;max-height:120px;display:block;margin:0 auto 16px auto;object-fit:contain;">
            <?php endif; ?>
            <p class="car-name"><?php echo htmlspecialchars($car_name) ?: '無車輛資訊'; ?></p>
        </div>
        <div class="charging-status">
            <div class="status-box">
                <h3>充電狀態</h3>
                <p class="status">充電中</p>
            </div>
            <div class="time-box">
                <h3>開始時間</h3>
                <p class="time"><?php echo date('Y-m-d H:i:s'); ?></p>
            </div>
        </div>
        <div class="progress-wrapper">
            <div class="progress-bar">
                <div id="progress-fill"></div>
            </div>
            <div class="progress-info">
                <span>充電進度：<span id="progress-percentage">0</span>%</span>
            </div>
        </div>
    </div>

    <script>
    // 模擬充電進度
    let progress = 0;
    const progressFill = document.getElementById('progress-fill');
    const progressPercentage = document.getElementById('progress-percentage');

    function updateProgress() {
        if (progress < 100) {
            progress += 1;
            progressFill.style.width = progress + '%';
            progressPercentage.textContent = progress;
            setTimeout(updateProgress, 1000);
        }
    }

    // 開始更新進度
    updateProgress();
    </script>
</body>
</html>