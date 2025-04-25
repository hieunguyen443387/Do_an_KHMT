<?php
include('config.php');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang đăng kí lịch thi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" 
          integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" 
          crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles.css">
</head>
<body onload="myChoice()"> 

    <div class="footer">
        <p>
            Bạn đang đăng nhập với tên 
            <u style="text-transform: uppercase;">
                <?php 
                    if (isset($_SESSION['msv'])) {
                        $msv = $_SESSION['msv'];

                        // Sử dụng prepared statement để tránh SQL injection
                        $stmt = $conn->prepare("SELECT ho_dem, ten FROM sinhvien WHERE msv = ?");
                        $stmt->bind_param("s", $msv);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($row = $result->fetch_assoc()) {
                            echo htmlspecialchars($row['ho_dem'] . " " . $row['ten']);
                        } else {
                            echo "Không tìm thấy sinh viên";
                        }

                        $stmt->close();
                    } else {
                        echo "Chưa đăng nhập";
                    }
                ?>
            </u> 
            <a href="logout.php">(Thoát)</a>
        </p>
    </div>

</body>
</html>
