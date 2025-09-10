<?php
// api/proxy/register.php
header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set('Asia/Taipei');

$TARGET_BASE = 'http://120.110.115.126:18081';
$ENDPOINT    = '/auth/register';

// --- 日誌 ---
$logDir  = __DIR__ . '/_logs';
if (!is_dir($logDir)) @mkdir($logDir, 0777, true);
$logFile = $logDir . '/register.' . date('Ymd') . '.log';
function logx($m){ global $logFile; @file_put_contents($logFile, '['.date('Y-m-d H:i:s').'] '.$m."\n", FILE_APPEND); }

// 只接受 POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  logx('BAD METHOD: '.$_SERVER['REQUEST_METHOD']);
  http_response_code(400);
  echo json_encode(['success'=>false,'code'=>400,'message'=>'Bad Request: method must be POST','data'=>null], JSON_UNESCAPED_UNICODE);
  exit;
}

// 讀 body
$raw = file_get_contents('php://input');
logx('REQ BODY: '.$raw);

// 簡單驗證 JSON
if (trim($raw) === '' || json_decode($raw, true) === null && json_last_error() !== JSON_ERROR_NONE) {
  logx('BAD JSON');
  http_response_code(400);
  echo json_encode(['success'=>false,'code'=>400,'message'=>'Bad JSON','data'=>null], JSON_UNESCAPED_UNICODE);
  exit;
}

// 轉發
$ch = curl_init($TARGET_BASE.$ENDPOINT);
curl_setopt_array($ch, [
  CURLOPT_POST           => true,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'Accept: */*'],
  CURLOPT_POSTFIELDS     => $raw,
  CURLOPT_TIMEOUT        => 30,
]);
$resp = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err  = curl_error($ch);
curl_close($ch);

if ($err) {
  logx('CURL ERR: '.$err);
  http_response_code(500);
  echo json_encode(['success'=>false,'code'=>500,'message'=>"proxy error: $err",'data'=>null], JSON_UNESCAPED_UNICODE);
  exit;
}

logx("RESP($http): ".$resp);
http_response_code($http ?: 200);
echo $resp;
