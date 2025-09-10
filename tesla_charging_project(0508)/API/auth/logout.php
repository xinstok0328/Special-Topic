<?php
// api/proxy/logout.php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

// 讀 JSON（若有）
$raw = file_get_contents('php://input');
$input = $raw ? json_decode($raw, true) : [];
if (!is_array($input)) $input = [];

// 從多個來源取得 token（任一即可）：
$token = trim($_SERVER['HTTP_AUTHORIZATION'] ?? '');     // Authorization header
if (!$token && isset($input['token'])) $token = trim((string)$input['token']); // JSON body
if (!$token && isset($_COOKIE['token'])) $token = trim((string)$_COOKIE['token']); // Cookie（若有）

// 正規化成 "Bearer xxx"
if ($token !== '' && stripos($token, 'Bearer ') !== 0) {
  $token = 'Bearer ' . $token;
}

// 沒 token 就回 401（避免上游 NPE=500）
if ($token === '') {
  http_response_code(401);
  echo json_encode(['success'=>false,'code'=>401,'message'=>'缺少 Authorization / token，無法登出','data'=>null],
                   JSON_UNESCAPED_UNICODE);
  exit;
}

$UPSTREAM = 'http://120.110.115.126:18081/auth/logout'; // 你的後端端點

$ch = curl_init($UPSTREAM);
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST           => true,                    // POST
  CURLOPT_HTTPHEADER     => ['Accept: */*', 'Authorization: '.$token],
  CURLOPT_POSTFIELDS     => '',                      // 根據 Swagger：body 空字串
  CURLOPT_CONNECTTIMEOUT => 10,
  CURLOPT_TIMEOUT        => 20,
]);

$body  = curl_exec($ch);
$errNo = curl_errno($ch);
$err   = curl_error($ch);
$http  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// ===== 代理側除錯日誌（可選，方便對方定位） =====
$log = __DIR__.'/logout.debug.log';
$meta = ['http'=>$http,'errNo'=>$errNo,'err'=>$err];
@file_put_contents(
  $log,
  "[".date('c')."] AUTH: ".substr($token,0,16)."…\nMETA: ".json_encode($meta,JSON_UNESCAPED_UNICODE)."\nRESPONSE: ".($body ?? '')."\n\n",
  FILE_APPEND
);
// ===============================================

if ($errNo) {
  http_response_code(502);
  echo json_encode(['success'=>false,'code'=>502,'message'=>'上游服務無回應：'.$err,'data'=>null],
                   JSON_UNESCAPED_UNICODE);
  exit;
}

http_response_code($http);
// 有些後端 logout 可能回空；為避免前端爆掉，給一個預設成功訊息
echo ($body !== false && $body !== null && $body !== '')
  ? $body
  : json_encode(['success'=>true,'code'=>0,'message'=>'已登出','data'=>null], JSON_UNESCAPED_UNICODE);
