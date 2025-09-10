<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db = "charging_station";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("連線失敗：" . $conn->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$car_brand = $_POST['car_brand'];
$car_model = $_POST['car_model'];

$stmt = $conn->prepare("INSERT INTO users (name, email, password, car_brand, car_model) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $email, $password, $car_brand, $car_model);

if ($stmt->execute()) {
  echo "<script>alert('註冊成功！'); window.location.href = '../html/login.html';</script>";
} else {
  echo "<script>alert('註冊失敗，帳號可能已存在'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>