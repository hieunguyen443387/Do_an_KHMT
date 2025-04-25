<?php 
    include('config.php'); 
    session_start();
    if (!isset($_SESSION['id_admin'])) {
        header("Location: ../home_admin/home_admin.html");
        exit();
    }

    $id_admin = $_SESSION['id_admin'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $mgv = $_POST['mgv'];
        $ho_dem = $_POST['ho_dem'];
        $ten = $_POST['ten'];
        $khoa = $_POST['khoa'];
        $ngay_sinh = $_POST['ngay_sinh'];
        $gioi_tinh = $_POST['gioi_tinh'];

        // Kiểm tra mã giảng viên đã tồn tại chưa
        $stmt_check = $conn->prepare("SELECT mgv FROM giangvien WHERE mgv = ?");
        $stmt_check->bind_param("s", $mgv);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0 ) {
            echo '<div class="alert">Mã giảng viên  đã tồn tại!</div>';
        } else {
            // Thêm giảng viên mới
            $stmt_insert = $conn->prepare("INSERT INTO giangvien (mgv, ho_dem, ten, khoa, ngay_sinh, gioi_tinh) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_insert->bind_param("ssssss", $mgv, $ho_dem, $ten, $khoa, $ngay_sinh, $gioi_tinh);

            if ($stmt_insert->execute()) {
                header("Location: manage_teacher.php");
                exit();
            } else {
                echo "Lỗi: " . $stmt_insert->error;
            }

            $stmt_insert->close();
        }

        $stmt_check->close();
    }
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="manage_add.css">
    <title>Trang quản lý lịch thi</title>
    
</head>
<body> 

    <div class="container-add">

        <div class="add-logo">
            <h3>Hệ thống quản lý lịch thi</h3>
            <h2>Đăng kí tài khoản giảng viên</h2>
        </div>

        <form action="" class="add-form" method="post">
            <div class="input-form">
                <input type="text" id="ho_dem" name="ho_dem" placeholder="Họ đệm" class="name" required>
                <input type="text" id="ten" name="ten" placeholder="Tên" class="name" required>
                <input type="text" id="mgv" name="mgv" placeholder="Mã giảng viên" required>
                <input type="text" id="khoa" name="khoa" placeholder="Khoa" required>
                <input type="date" id="ngay_sinh" name="ngay_sinh">
            </div>
            <div class="gender">
                <input type="radio" id="male" name="gioi_tinh" value="Nam" required>
                <label for="male">Nam</label>
                <input type="radio" id="female" name="gioi_tinh" value="Nữ" required>
                <label for="female">Nữ</label><br>               
            </div>
            <br>
            <button type="submit">Nhập</button>
        </form>

    </div>       
        
    <?php include('footer.php'); ?>  

</body>
</html>
