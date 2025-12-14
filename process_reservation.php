<?php
// 1. 資料庫連接設定 - 請務必修改以下參數！
$servername = "localhost"; // 資料庫主機名稱 (通常是 localhost)
$username = "root"; // MySQL 使用者名稱
$password = "12345678"; // MySQL 密碼
$dbname = "test_01"; // 資料庫名稱 (應包含 reservations 表格)

// 2. 連接資料庫
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接是否成功
if ($conn->connect_error) {
    die("資料庫連接失敗: " . $conn->connect_error);
}

// 3. 確認資料是以 POST 方法提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $restaurantId = (int)$_POST['restaurant_id'];
    // 4. 接收並清理表單數據
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    // 確保 guests 是整數，並進行基本清理
    $guests = (int)$_POST['guests']; 
    $time = $_POST['time']; // datetime-local 格式為 YYYY-MM-DDTHH:MM，MySQL會自動轉換

    // 5. 準備 SQL 語句
    // 這是最安全的方式：使用預處理語句 (Prepared Statements) 來防止 SQL 隱碼攻擊
    $sql = "INSERT INTO reservations (restaurant_id, name, phone, guests, reservation_time) VALUES (?, ?, ?, ?, ?)";
    
    // 準備並綁定參數
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die('SQL 準備失敗: ' . $conn->error);
    }
    
    // 'siss' 代表綁定四個參數的類型：String, Integer, String, String (datetime-local 在 PHP 中當作字串)
    $stmt->bind_param("issss", $restaurantId, $name, $phone, $guests, $time);

    // 6. 執行並處理結果
    if ($stmt->execute()) {
        echo "<script>alert('預約成功！資料已送出。'); window.location.href='index.html';</script>";
    } else {
        echo "預約失敗，錯誤訊息: " . $stmt->error;
    }

    // 關閉語句和連接
    $stmt->close();
} else {
    // 如果不是 POST 方式提交，導回表單頁面
    header("Location: house.html");
}

$conn->close();

?>