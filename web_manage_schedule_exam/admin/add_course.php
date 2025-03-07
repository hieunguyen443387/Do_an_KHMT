<?php 
    include('config.php'); 
    session_start();
    if (!isset($_SESSION['id_admin'])) {
        header("Location: ../home_admin/home_admin.html");
        exit();
    }

    $id_admin = $_SESSION['id_admin'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $ma_hoc_phan = $_POST['ma_hoc_phan'];
        $ten_hoc_phan = $_POST['ten_hoc_phan'];
        $so_tin_chi = $_POST['so_tin_chi'];

        $sql_hoc_phan = "SELECT * FROM hocphan WHERE ma_hoc_phan = '$ma_hoc_phan'";
        $result_hoc_phan = $conn->query($sql_hoc_phan);
        if ($result_hoc_phan->num_rows > 0 ) {
            echo "Đã tồn tại";
        } else {

            // Chèn dữ liệu vào bảng
            $sql_hoc_phan = "INSERT INTO hocphan (ma_hoc_phan, ten_hoc_phan, so_tin_chi) 
            VALUES ('$ma_hoc_phan', '$ten_hoc_phan', '$so_tin_chi')";
            

            if ($conn->query($sql_hoc_phan) === TRUE ) {
                header("Location:manage_course.php");
                exit();
            } else {
                echo "Error: " . $sql_hoc_phan . "<br>" . $conn->error;
            }
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
            <h2>Thêm phòng thi</h2>
        </div>

        <form action="" class="add-form" method="post">
            <div class="input-form">
                <input type="text" id="ma_hoc_phan" name="ma_hoc_phan" placeholder="Mã học phần" required>
                <input type="text" id="ten_hoc_phan" name="ten_hoc_phan" placeholder="Tên học phần" required>
                <input type="number" id="so_tin_chi" name="so_tin_chi" placeholder="Số tín chỉ" min="1" max="4" step="1" required>
            </div>
            <br>
            <button type="submit">Nhập</button>
        </form>

    </div>       
        
    <?php include('footer.php'); ?>  

</body>
</html>
