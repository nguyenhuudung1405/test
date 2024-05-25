<?php
session_start();
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
   exit();
}
$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "login_register";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
// Hàm tính tiền điện

$sql = "SELECT * FROM bacsodien";
$result = $conn->query($sql);

$bac_gia_dien = [];
$bac1 = $bac2 = $bac3 = $bac4 = $bac5 = $bac6 = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bac_gia_dien[] = $row; // Lưu trữ dữ liệu vào mảng để sử dụng sau

        // Kiểm tra và gán giá trị cho các biến
        if (isset($row["id"]) && isset($row["gia_tien"])) {
            switch ($row["id"]) {
                case 1:
                    $bac1 = $row["gia_tien"];
                    break;
                case 2:
                    $bac2 = $row["gia_tien"];
                    break;
                case 3:
                    $bac3 = $row["gia_tien"];
                    break;
                case 4:
                    $bac4 = $row["gia_tien"];
                    break;
                case 5:
                    $bac5 = $row["gia_tien"];
                    break;
                case 6:
                    $bac6 = $row["gia_tien"];
                    break;
                default:
                    echo "ID không hợp lệ: " . $row["id"];
                    break;
            }
        } else {
            echo "Thiếu cột id hoặc gia_tien trong kết quả truy vấn.";
        }
    }
} else {
    echo "Không có dữ liệu bậc giá điện.";
}

// Hàm tính tiền điện
function tinhTienDien($soDien, $bac1, $bac2, $bac3, $bac4, $bac5, $bac6) {
    $tienDien = 0;

    if ($soDien < 0) {
        return "Số điện không được nhỏ hơn 0";
    } elseif ($soDien <= 50) {
        $tienDien = $soDien * $bac1;
    } elseif ($soDien <= 100) {
        $tienDien = 50 * $bac1 + (($soDien - 50) * $bac2);
    } elseif ($soDien <= 200) {
        $tienDien = 50 * $bac1 + 50 * $bac2 + (($soDien - 100) * $bac3);
    } elseif ($soDien <= 300) {
        $tienDien = 50 * $bac1 + 50 * $bac2 + 100 * $bac3 + (($soDien - 200) * $bac4);
    } elseif ($soDien <= 400) {
        $tienDien = 50 * $bac1 + 50 * $bac2 + 100 * $bac3 + 100 * $bac4 + (($soDien - 300) * $bac5);
    } else {
        $tienDien = 50 * $bac1 + 50 * $bac2 + 100 * $bac3 + 100 * $bac4 + 100 * $bac5 + (($soDien - 400) * $bac6);
    }

    return $tienDien;
}

// Kiểm tra xem đã nhận số điện mới từ form chưa
if (isset($_POST["so_dien_moi"])) {
    $soDienMoi = $_POST["so_dien_moi"];
    $tienDien = tinhTienDien($soDienMoi, $bac1, $bac2, $bac3, $bac4, $bac5, $bac6);
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
    <title>Báo Cáo Tiền Điện</title>
    <style>
    
    </style>
</head>
<body>
<div class="container">   
        <h1>Báo Cáo Tiền Điện</h1>
        <?php if (isset($soDienMoi) && isset($tienDien)): ?>
            <div class="report">
                <p>Số điện mới: <?php echo htmlspecialchars($soDienMoi); ?> kWh</p>
                <p>Tổng tiền điện: <?php echo htmlspecialchars($tienDien); ?> VND</p>
            </div>
        <?php else: ?>
            <p>Không có dữ liệu để hiển thị báo cáo.</p>
        <?php endif; ?>

        <h2>Bảng giá điện</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Bậc</th>
                    <th>Giá tiền (VND/kWh)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bac_gia_dien as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['bac_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['gia_tien']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="index.php" class="btn btn-primary">Quay lại Trang Chính</a>
    </div>
</body>
</html>

<?php
$conn->close();
?>