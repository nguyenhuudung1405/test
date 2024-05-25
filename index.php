<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
   exit();
}

require_once "database.php";

function tinhTienDien($soDien) {
    global $conn; // Sử dụng biến kết nối đến cơ sở dữ liệu


    $sql = "SELECT * FROM bacsodien";
    $result = $conn->query($sql);

    // Khởi tạo biến chứa tổng số tiền điện
    $tienDien = 0;

    if ($result->num_rows > 0) {
        // Duyệt qua từng bậc giá tiền
        while ($row = $result->fetch_assoc()) {
            $start_range = $row['start_range'];
            $end_range = $row['end_range'];
            $gia_tien = $row['gia_tien'];

            // Nếu số điện nằm trong khoảng của bậc này
            if ($soDien >= $start_range && $soDien <= $end_range) {
                // Tính tiền điện cho bậc này
                $tienDien += ($soDien - $start_range + 1) * $gia_tien; 
            }
        }
    }

    return $tienDien;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["so_dien_moi"])) {
       
        $soDienMoi = $_POST["so_dien_moi"];

        
        $tienDien = tinhTienDien($soDienMoi);

        
        echo "Số tiền điện cần thanh toán cho $soDienMoi kWh là: $tienDien VND";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .btn {
            padding: 10px 15px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #218838;
        }
        .report {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">   
    <h1>Quản Lý Tiền Điện</h1>
    
    <form method="post" action="calculate.php">

            <div class="form-group">
                
                <label for="so_dien_moi">Nhập số điện mới (kWh):</label>
                <input type="number" id="so_dien_moi" name="so_dien_moi" required>
            </div>
            <button type="submit" class="btn">Tính Tiền Điện</button>
        </form>
  <form method="post" action="update_prices.php">
    <div class="form-group">
        <label for="gia_bac1">Giá tiền bậc 1 (VNĐ/kWh):</label>
        <input type="number" id="gia_bac1" name="gia_bac1" required>
    </div>
    <div class="form-group">
        <label for="gia_bac2">Giá tiền bậc 2 (VNĐ/kWh):</label>
        <input type="number" id="gia_bac2" name="gia_bac2" required>
    </div>
    <div class="form-group">
        <label for="gia_bac2">Giá tiền bậc 3 (VNĐ/kWh):</label>
        <input type="number" id="gia_bac3" name="gia_bac3" required>
    </div>
    <div class="form-group">
        <label for="gia_bac2">Giá tiền bậc 4 (VNĐ/kWh):</label>
        <input type="number" id="gia_bac4" name="gia_bac4" required>
    </div>
    <div class="form-group">
        <label for="gia_bac2">Giá tiền bậc 5 (VNĐ/kWh):</label>
        <input type="number" id="gia_bac5" name="gia_bac5" required>
    </div>
    <div class="form-group">
        <label for="gia_bac2">Giá tiền bậc 6 (VNĐ/kWh):</label>
        <input type="number" id="gia_bac6" name="gia_bac6" required>
    </div>
    <button type="submit" class="btn">Cập Nhật Giá Tiền</button>
</form>
        <a href="logout.php" class="btn btn-warning">Logout</a>
        
    </div>
    
    
</body>
</html>
