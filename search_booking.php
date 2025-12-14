<?php
header('Content-Type: application/json'); // 確保回傳 JSON 格式
// 設置資料庫配置 (請替換為您的實際資訊)
$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "test_01";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => '資料庫連接失敗']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);

    // 關鍵查詢：聯結 reservations 和 restaurants 表格
    $sql = "SELECT r.reservation_id, r.name, r.phone, r.guests, r.reservation_time, 
                   rest.name AS restaurant_name
            FROM reservations r
            JOIN restaurants rest ON r.restaurant_id = rest.restaurant_id
            WHERE r.name = ? AND r.phone = ?";
    
    // 使用預處理語句確保安全
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $name, $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    $reservations = [];
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }

    // 返回成功狀態和查詢結果
    echo json_encode(['status' => 'success', 'reservations' => $reservations]);

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => '無效的請求方法']);
}

$conn->close();
?>