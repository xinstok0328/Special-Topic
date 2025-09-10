// js/login-handler.js
document.getElementById("loginForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const account = document.getElementById("account").value.trim();
    const password = document.getElementById("password").value.trim();

    if (!account || !password) {
        alert("請輸入帳號與密碼");
        return;
    }

    // 切換直連 / 代理模式
    const useProxy = false; // true = 走 PHP 代理, false = 直連 API
    const baseURL = useProxy ? "/api/proxy/login.php" : "http://120.110.115.126:18081/auth/login";

    try {
        const response = await fetch(baseURL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ account, password })
        });

        const data = await response.json();

        if (data.success) {
            alert("登入成功！");
            localStorage.setItem("token", data.data.token); // 假設 API 回傳 token
            window.location.href = "dashboard.html"; // 登入後導頁
        } else {
            alert(`登入失敗：${data.message}`);
        }
    } catch (error) {
        console.error("登入錯誤：", error);
        alert("無法連線到伺服器");
    }
});

