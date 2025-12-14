<?php
// db_connect.php

$host = 'localhost';
$db_name = 'time_to_booking';
$username = 'root'; // XAMPP 的預設使用者名稱
$password = '12345678'; // XAMPP 的預設密碼是空字串

// 設定 DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$db_name;charset=utf8mb4";

// 設定 PDO 選項
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // 發生錯誤時拋出例外
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // 取得關聯式陣列
    PDO::ATTR_EMULATE_PREPARES   => false, // 停用模擬預備語句
];

// 嘗試建立 PDO 連線
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // 連線失敗，顯示錯誤訊息
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>