<?php
/**
 * api/proxy/login.php
 * 將前端送來的 {account,password} 轉發到上游 API：
 *   http://120.110.115.126:18081/auth/login
 */
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: POST, OPTIONS');

// === login 代理除錯日誌（密碼掩碼 + 簡易輪替）=========================
function log_proxy_login(array $request, int $http, int $errNo, string $err, $body): void {
  // 日誌檔路徑：會寫在 api/proxy/login.debug.log
  $logFile = __DIR__ . '/login.debug.log';

  // 密碼做掩碼（保留最後 2 碼）
  $masked = $request;
  if (isset($masked['password'])) {
    $pwd = (string)$masked['password'];
    $masked['password'] = str_repeat('*', max(0, strlen($pwd) - 2)) . substr($pwd, -2);
  }

  // >1MB 就輪替
  if (@file_exists($logFile) && @filesize($logFile) > 1024 * 1024) {
    @rename($logFile, $logFile . '.' . date('Ymd_His'));
  }

  // 組日誌內容
  $meta = [
    'http'  => $http,
    'errNo' => $errNo,
    'err'   => $err,
  ];
  $line = sprintf(
    "[%s]\nREQUEST: %s\nMETA: %s\nRESPONSE: %s\n\n",
    date('c'),
    json_encode($masked, JSON_UNESCAPED_UNICODE),
    json_encode($meta,   JSON_UNESCAPED_UNICODE),
    is_string($body) ? $body : json_encode($body, JSON_UNESCAPED_UNICODE)
  );

  @file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
}
// ====================================================================


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(204);
  exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input) || !isset($input['account'], $input['password'])) {
  http_response_code(400);
  echo json_encode(['success'=>false,'code'=>400,'message'=>'缺少 account 或 password','data'=>null],
                   JSON_UNESCAPED_UNICODE);
  exit;
}

$UPSTREAM = 'http://120.110.115.126:18081/auth/login';

$ch = curl_init($UPSTREAM);
curl_setopt_array($ch, [
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_POST           => true,
  CURLOPT_HTTPHEADER     => ['Content-Type: application/json','Accept: */*'],
  CURLOPT_POSTFIELDS     => json_encode([
    'account'  => $input['account'],
    'password' => $input['password'],
  ], JSON_UNESCAPED_UNICODE),
  CURLOPT_CONNECTTIMEOUT => 10,
  CURLOPT_TIMEOUT        => 20,
]);

$body = curl_exec($ch);
$errNo = curl_errno($ch);
$err   = curl_error($ch);
$http  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($errNo) {

   log_proxy_login($input ?? [], 0, $errNo, $err, $body);

  http_response_code(502);
  echo json_encode(['success'=>false,'code'=>502,'message'=>'上游服務無回應：'.$err,'data'=>null],
                   JSON_UNESCAPED_UNICODE);
  exit;
}

log_proxy_login($input ?? [], $http, 0, '', $body);

http_response_code($http);
echo ($body !== false && $body !== null && $body !== '')
  ? $body
  : json_encode(['success'=>false,'code'=>$http,'message'=>'上游回應為空','data'=>null],
                JSON_UNESCAPED_UNICODE);
