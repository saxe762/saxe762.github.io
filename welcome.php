<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
    header ('Content-Type: text/html; charset=utf-8');
// 是否是表單送回
if (isset($_POST["Insert"])) {
   // 開啟MySQL的資料庫連接
   $link = @mysqli_connect("localhost","root","12345678") 
         or die("無法開啟MySQL資料庫連接!<br/>");
   mysqli_select_db($link, "myschool");  // 選擇資料庫
   // 建立新增記錄的SQL指令字串
$sql ="INSERT INTO courses (cno,pname,title,credits) VALUES ('" . $_POST["cno"] . "','" . $_POST["pname"] . "','" . $_POST["title"] . "','" . $_POST["credits"] ."')";
$sql2 = "SELECT * FROM courses ";
echo "<b>SQL指令: $sql</b><br/>";
   // 送出Big5編碼的MySQL指令
   mysqli_query($link, "SET CHARACTER SET utf8");
   mysqli_query($link, "SET collation_connection = 'utf8_unicode_ci'");
if ( mysqli_query($link, $sql) ) // 執行SQL指令
    if ( $result = mysqli_query($link, $sql2) ) { 
    echo "<b>課程資料:</b><br/>";  // 顯示查詢結果
    while( $row = mysqli_fetch_array($result) ){ 
       echo $row[0]."-".$row[1]."-".$row[2]."-".$row[3]."<br/>";
    }     
    mysqli_free_result($result); // 釋放佔用記憶體
 } 
else
    die("資料庫新增記錄失敗<br/>");
   mysqli_close($link);      // 關閉資料庫連接
} ?>   
</body>
</html>