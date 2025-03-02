<?php
    include('config.php'); 
    session_start();
    if (isset($_GET['msv'])) {
        $msv = $_GET['msv'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ho_dem = $_POST['ho_dem'];
            $ten = $_POST['ten'];
            $khoa = $_POST['khoa'];
            $lop = $_POST['lop'];
            $ngay_sinh = $_POST['ngay_sinh'];
            $gioi_tinh = $_POST['gioi_tinh'];
            $part = (explode("-",$ngay_sinh));
            $mat_khau = $part[2] . $part[1] . $part[0];       
    
            // Cập nhật dữ liệu vào bảng
            $sql_sinh_vien = "UPDATE sinhvien set ho_dem = '$ho_dem', ten = '$ten', khoa = '$khoa', mat_khau = '$mat_khau', ngay_sinh = '$ngay_sinh', gioi_tinh = '$gioi_tinh', lop = '$lop' WHERE msv = '$msv'";         

            if ($conn->query($sql_sinh_vien) === TRUE ) {
                header("Location:manage_student.php");
                exit();
            } else {
                echo "Error: " . $sql_sinh_vien . "<br>" . $conn->error;
            }
            
        }
    }

    $sql_sinh_vien = "SELECT * FROM sinhvien WHERE msv = '$msv'";
    $result_sinh_vien = $conn->query($sql_sinh_vien);
    if ($result_sinh_vien->num_rows > 0) {
        $stt = 1;
        while($row = $result_sinh_vien->fetch_assoc()) {
            $ho_dem = $row["ho_dem"];
            $ten = $row["ten"];
            $lop = $row["lop"];
            $khoa = $row["khoa"];
            $ngay_sinh = $row["ngay_sinh"];
            $gioi_tinh = $row["gioi_tinh"];
        }
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
            <h2>Cập nhật tài khoản sinh viên: <?php echo $msv; ?></h2>
        </div>

        <form action="" class="add-form" method="post">
            <div class="input-form">
                <input type="text" id="ho_dem" name="ho_dem" placeholder="Họ đệm" class="name" required value = "<?php echo $ho_dem; ?>">
                <input type="text" id="ten" name="ten" placeholder="Tên" class="name" required value = "<?php echo $ten; ?>">
                <input type="text" id="khoa" name="khoa" placeholder="Khoa" required value = "<?php echo $khoa; ?>">
                <input type="text" id="lop" name="lop" placeholder="Lớp" required value = "<?php echo $lop; ?>">
                <input type="date" id="ngay_sinh" name="ngay_sinh" value = "<?php echo $ngay_sinh; ?>">
            </div>
            <div class="gender">
                <?php 
                    $sql_sinh_vien = "SELECT * FROM sinhvien WHERE msv = '$msv'";
                    $result_sinh_vien = $conn->query($sql_sinh_vien);
                    $pass_sinh_vien = $result_sinh_vien->fetch_assoc();
                    echo '<input type="radio" id="male" name="gioi_tinh" value="Nam"';
                    if ($pass_sinh_vien['gioi_tinh'] ==  "Nam") {
                        echo ' checked';
                    } 
                    echo '>';
                    echo '<label for="male" >Nam</label>';
                    echo '<input type="radio" id="female" name="gioi_tinh" value="Nữ"';
                    if ($pass_sinh_vien['gioi_tinh'] ==  "Nữ") {
                        echo ' checked';
                    }
                    echo '>';
                    echo '<label for="female">Nữ</label><br>';
                ?>         
            </div>
            <br>
            <button type="submit">Cập nhật</button>
        </form>

    </div>       
        
    <?php include('footer.php'); ?>

</body>
</html>
