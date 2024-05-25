<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
   exit();
}

require_once "database.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy giá trị nhập vào từ form
    $giaBac1 = $_POST["gia_bac1"];
    $giaBac2 = $_POST["gia_bac2"];
    $giaBac3 = $_POST["gia_bac3"];
    $giaBac4 = $_POST["gia_bac4"];
    $giaBac5 = $_POST["gia_bac5"];
    $giaBac6 = $_POST["gia_bac6"];
    // Lấy giá trị nhập vào cho các bậc tiếp theo tương tự

    // Cập nhật giá tiền vào cơ sở dữ liệu
    $sql = "UPDATE bacsodien SET gia_tien = CASE id 
                WHEN 1 THEN $giaBac1
                WHEN 2 THEN $giaBac2
                WHEN 3 THEN $giaBac3
                WHEN 4 THEN $giaBac4
                WHEN 5 THEN $giaBac5
                WHEN 6 THEN $giaBac6
                -- Thêm các trường cho các bậc tiếp theo tương tự
            END";
    if ($conn->query($sql) === TRUE) {
        echo "Cập nhật giá tiền thành công";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>
