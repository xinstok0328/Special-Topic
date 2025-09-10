<?php /* 純前端頁面，請勿輸出 JSON 或處理帳密 */ ?>
<!doctype html>
<html lang="zh-Hant">
<head>
  <meta charset="utf-8" />
  <title>會員登入｜智慧充電樁管理平台</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="../css/ev-green-login.css" />
  <style>
    .login-card { max-width: 420px; margin: 6rem auto; padding: 2rem; border-radius: 16px; background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,.08);}
    .login-card h1 { font-size: 20px; margin: 0 0 1rem; }
    .login-card label { display:block; font-size: 14px; margin-top: .75rem; color:#333 }
    .login-card input { width:100%; padding:.75rem .9rem; font-size:16px; border:1px solid #d9d9d9; border-radius:10px; outline: none; }
    .row { display:flex; gap:.5rem; align-items:center }
    .note { color:#c00; min-height:1.5rem; margin-top:.5rem }
    .btn { width:100%; padding:.8rem; border:0; border-radius:12px; background:#2db36a; color:#fff; font-size:16px; cursor:pointer }
    .btn[disabled] { filter:grayscale(1); cursor:not-allowed }
    .link { background:#f0f8f4; color:#2db36a; }
  </style>
</head>
<body>
  <div class="login-card">
    <h1>請輸入帳號與密碼</h1>

    <form id="loginForm" novalidate>
      <label for="account">帳號 Email</label>
      <input id="account" name="account" autocomplete="username" required />

      <label for="password">密碼</label>
      <div class="row">
        <input id="password" name="password" type="password" autocomplete="current-password" required />
        <button type="button" id="togglePw" class="link">顯示</button>
      </div>

      <p id="msg" class="note"></p>

      <button id="submitBtn" type="submit" class="btn">登入</button>
    </form>
  </div>

  <script type="module">
    import { saveToken } from '../js/auth.js';

    const $  = s => document.querySelector(s);
    const form = $('#loginForm');
    const btn  = $('#submitBtn');
    const msg  = $('#msg');
    const acc  = $('#account');
    const pw   = $('#password');

    acc.value = localStorage.getItem('last_account') || '';

    $('#togglePw')?.addEventListener('click', () => {
      pw.type = pw.type === 'password' ? 'text' : 'password';
    });

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      msg.textContent = '';
      const account  = acc.value.trim();
      const password = pw.value;

      if (!account || !password) {
        msg.textContent = '請填寫帳號與密碼';
        return;
      }

      btn.disabled = true;
      btn.textContent = '登入中…';

      try {
        const res = await fetch('/api/proxy/login.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ account, password })
        });
        const data = await res.json().catch(() => ({}));
        const tokenLike = data?.data || data?.token || data?.access_token;

        if (res.ok && tokenLike) {
          localStorage.setItem('last_account', account);
          saveToken(data);
          location.href = '/html/charging.html';
        } else {
          msg.textContent = data?.message || '登入失敗，請確認帳密';
        }
      } catch (err) {
        console.error(err);
        msg.textContent = '網路異常，請稍後再試';
      } finally {
        btn.disabled = false;
        btn.textContent = '登入';
      }
    });
  </script>
</body>
</html>
