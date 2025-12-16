<?php
// update_booking.php
header('Content-Type: application/json');

// 1. 資料庫連接設定 (請替換為您的實際資訊)
$servername = "192.168.80.108";
$username = "root";
$password = "12345678";
$dbname = "time_to_booking";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => '資料庫連接失敗: ' . $conn->connect_error]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reservation_id'], $_POST['guests'], $_POST['time'])) {
    
    // 接收並清理數據
    $reservationId = (int)$_POST['reservation_id'];
    $newGuests = (int)$_POST['guests'];
    $newTime = $_POST['time']; // 接收格式為 YYYY-MM-DDTHH:MM，MySQL 會自動轉換

    // 2. 關鍵操作：執行 UPDATE 語句
    // 僅允許修改人數和時間
    $sql = "UPDATE reservations SET guests = ?, reservation_time = ? WHERE reservation_id = ?";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'SQL 準備失敗: ' . $conn->error]);
        $conn->close();
        exit();
    }
    
    // 綁定參數： i (int - guests), s (string - time), i (int - reservation_id)
    $stmt->bind_param("isi", $newGuests, $newTime, $reservationId);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => '✅ 訂位已成功修改！']);
        } else {
            // 如果 affected_rows 是 0，表示 WHERE 條件吻合，但資料沒有變動
            echo json_encode(['status' => 'fail', 'message' => '訂位資料未變更。']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => '修改失敗: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => '無效的修改請求或缺少參數']);
}

$conn->close();
?>