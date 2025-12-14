<?php
header('Content-Type: application/json');
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reservation_id'])) {
    $reservationId = (int)$_POST['reservation_id'];

    // 關鍵操作：刪除記錄
    $sql = "DELETE FROM reservations WHERE reservation_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservationId);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => '✅ 訂位已成功取消！']);
        } else {
            echo json_encode(['status' => 'fail', 'message' => '該訂位可能已不存在。']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => '取消失敗: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => '無效的取消請求']);
}

$conn->close();
?>