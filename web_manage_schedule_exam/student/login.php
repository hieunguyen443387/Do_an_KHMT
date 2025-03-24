<?php
session_start();
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['msv']) && isset($_POST['mat_khau'])) {
        $msv = trim($_POST['msv']);
        $mat_khau = trim($_POST['mat_khau']); 

        // Truy vấn lấy mật khẩu băm từ database
        $sql = "SELECT msv, mat_khau FROM sinhvien WHERE msv = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $msv); 
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            // Kiểm tra mật khẩu nhập vào với mật khẩu đã băm
            if (password_verify($mat_khau, $row['mat_khau'])) {
                $_SESSION['msv'] = $msv;
                header("Location: home_student.php");
                exit();
            } else {
                echo "Mật khẩu không đúng.";
            }
        } else {
            echo "Mã sinh viên không tồn tại.";
        }

        $stmt->close();
    } else {
        echo "Vui lòng nhập đầy đủ thông tin.";
    }
}


$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="login.css">
    <title>Trang quản lý lịch thi</title>
    
</head>
<body> 

    <div class="container-login">

        <div class="logo">
            <h3>Hệ thống quản lý lịch thi</h3>
            <h2>Đăng nhập tài khoản sinh viên</h2>
        </div>

        <form action="" class="login-form" method="post">
            <div class="input-form">
                <input type="text" id="msv" name="msv" placeholder="Tên đăng nhập (username)" required>
                <input type="password" id="mat_khau" name="mat_khau" placeholder="Mật khẩu" required>
            </div>
            <br>
            <button type="submit">Đăng nhập</button>
        </form>

    </div>       
        
    <div class="footer">
        <p>Bạn chưa đăng nhập</p>
        <p><a href="home_page.php">Trang chủ</a></p>
    </div>

</body>
</html>
