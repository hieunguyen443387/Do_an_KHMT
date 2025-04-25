<?php 
    session_start(); 
    include('config.php');
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $msv = $_POST['msv'];
        $ho_dem = $_POST['ho_dem'];
        $ten = $_POST['ten'];
        $khoa = $_POST['khoa'];
        $lop = $_POST['lop'];
        $ngay_sinh = $_POST['ngay_sinh'];
        $gioi_tinh = $_POST['gioi_tinh'];

        // Mã hóa mật khẩu
        $part = explode("-", $ngay_sinh);
        $mat_khau_raw = $part[2] . $part[1] . $part[0]; // ddmmyyyy
        $mat_khau = password_hash($mat_khau_raw, PASSWORD_DEFAULT);

        // Kiểm tra sự tồn tại của MSV trong database
        $stmt_check = $conn->prepare("SELECT msv FROM sinhvien WHERE msv = ?");
        $stmt_check->bind_param("s", $msv);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            echo '<div class="alert">Mã sinh viên đã tồn tại!</div>';
        } else {
            // Chèn dữ liệu vào bảng sinhvien
            $stmt_insert = $conn->prepare("INSERT INTO sinhvien (msv, ho_dem, ten, khoa, mat_khau, ngay_sinh, gioi_tinh, lop) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_insert->bind_param("ssssssss", $msv, $ho_dem, $ten, $khoa, $mat_khau, $ngay_sinh, $gioi_tinh, $lop);

            if ($stmt_insert->execute()) {
                header("Location: manage_student.php");
                exit();
            } else {
                // Kiểm tra lỗi của câu lệnh INSERT
                echo "Lỗi thêm sinh viên: " . $stmt_insert->error;
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
            <h2>Đăng kí tài khoản sinh viên</h2>
        </div>

        <form action="" class="add-form" method="post">
            <div class="input-form">
                <input type="text" id="ho_dem" name="ho_dem" placeholder="Họ đệm" class="name" required>
                <input type="text" id="ten" name="ten" placeholder="Tên" class="name" required>
                <input type="text" id="msv" name="msv" placeholder="Mã sinh viên" required>
                <input type="text" id="lop" name="lop" placeholder="Lớp" required>
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
