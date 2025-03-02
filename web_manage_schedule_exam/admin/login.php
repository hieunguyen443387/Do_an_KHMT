<?php
session_start(); // Bắt đầu session

include('config.php');


// Kiểm tra xem form có được gửi đi hay không
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kiểm tra xem các biến có tồn tại hay không
    if (isset($_POST['id_admin']) && isset($_POST['mat_khau'])) {
        $id_admin = $_POST['id_admin'];
        $mat_khau = $_POST['mat_khau'];

        // Kiểm tra thông tin đăng nhập
        $sql = "SELECT id_admin FROM admin WHERE id_admin = '$id_admin' AND mat_khau = '$mat_khau'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $_SESSION['id_admin'] = $id_admin;
            header("Location: home_admin.php");
            exit();
        } else {
            echo "Mã admin hoặc mật khẩu không đúng.";
        }
    } else {
        echo "Vui lòng nhập tên đăng nhập và mật khẩu.";
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
            <h2>Đăng nhập tài khoản quản trị</h2>
        </div>

        <form action="login.php" class="login-form" method="post">
            <div class="input-form">
                <input type="text" id="id_admin" name="id_admin" placeholder="Tên đăng nhập (username)" required>
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
