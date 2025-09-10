// js/auth.js — 直連你的後端 API（無經過 PHP 代理）
// 後端根網址
const BACKEND = 'http://120.110.115.126:18081';

// 固定路徑
const PATH = {
  login:    '/auth/login',
  register: '/auth/register',
  logout:   '/auth/logout',
};

// Token 儲存鍵與預設有效時間（2 小時）
const TOKEN_KEY = 'token';
const EXP_KEY   = 'token_exp'; // 毫秒 timestamp
const DEFAULT_HOURS = 2;

/** 存 Token：支援字串或物件（token / access_token / data.token / expires_in） */
export function saveToken(input, defaultHours = DEFAULT_HOURS) {
  let token = '';
  let ttlMs = defaultHours * 60 * 60 * 1000;

  if (typeof input === 'string') {
    token = input;
  } else if (input && typeof input === 'object') {
    token =
      input.token ||
      input.access_token ||
      (typeof input.data === 'string' ? input.data : input.data?.token) ||
      '';
    if (Number.isFinite(input.expires_in)) {
      ttlMs = input.expires_in * 1000;
    }
  }

  if (!token) return false;
  localStorage.setItem(TOKEN_KEY, token);
  localStorage.setItem(EXP_KEY, String(Date.now() + ttlMs));
  return true;
}

export function getToken() {
  const exp = parseInt(localStorage.getItem(EXP_KEY) || '0', 10);
  if (exp && Date.now() > exp) {
    clearToken();
    return null;
  }
  return localStorage.getItem(TOKEN_KEY);
}

export function clearToken() {
  localStorage.removeItem(TOKEN_KEY);
  localStorage.removeItem(EXP_KEY);
}

export function isLoggedIn() {
  return !!getToken();
}

/** 需要登入的頁面可呼叫：沒 token 直接導回登入頁 */
export function guard(loginPath = '/html/login.php') {
  if (!isLoggedIn()) location.replace(loginPath);
}

/** 包裝 fetch：自動帶 Authorization；401/403 會清 token 並導回登入 */
export async function authFetch(endpoint, options = {}, loginPath = '/html/login.php') {
  const t = getToken();
  const headers = {
    'Accept': '*/*',
    ...(options.headers || {}),
    ...(t ? { Authorization: t.startsWith('Bearer ') ? t : `Bearer ${t}` } : {}),
  };

  const res = await fetch(`${BACKEND}${endpoint}`, { ...options, headers });
  if (res.status === 401 || res.status === 403) {
    clearToken();
    location.replace(loginPath);
    return res;
  }
  return res;
}

/** 登入：POST /auth/login */
export async function login(account, password) {
  const res = await fetch(`${BACKEND}${PATH.login}`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'Accept': '*/*' },
    body: JSON.stringify({ account, password })
  });
  const data = await res.json().catch(() => ({}));
  // 儲存 token（支援多種回傳格式）
  saveToken(data);
  return { ok: res.ok, status: res.status, data };
}

/** 註冊：POST /auth/register */
export async function registerUser(payload) {
  // 清空值：把空字串/undefined 移除
  const clean = {};
  Object.entries(payload || {}).forEach(([k, v]) => {
    if (v !== undefined && v !== null && String(v).trim() !== '') clean[k] = v;
  });

  const res = await fetch(`${BACKEND}${PATH.register}`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'Accept': '*/*' },
    body: JSON.stringify(clean)
  });
  const data = await res.json().catch(() => ({}));
  return { ok: res.ok, status: res.status, data };
}

/** 登出：POST /auth/logout（需帶 Bearer token） */
export async function logout() {
  const res = await authFetch(PATH.logout, { method: 'POST' });
  const data = await res.json().catch(() => ({}));
  clearToken(); // 無論後端回什麼都先清掉
  return { ok: res.ok, status: res.status, data };
}
