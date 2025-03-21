<?php 
    include('config.php'); 
    session_start();
    if (!isset($_SESSION['id_admin'])) {
        header("Location: ../home_admin/home_admin.html");
        exit();
    }

    $id_admin = $_SESSION['id_admin'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $ma_khoa = $_POST['ma_khoa'];
        $ten_khoa = $_POST['ten_khoa'];

        $sql_khoa = "SELECT ma_khoa FROM khoa WHERE ma_khoa = '$ma_khoa'";
        $result_khoa  = $conn->query($sql_khoa);
        if ($result_khoa->num_rows > 0 ) {
            echo "Đã tồn tại";
        } else {

            // Chèn dữ liệu vào bảng
            $sql_khoa = "INSERT INTO khoa (ma_khoa, ten_khoa) 
            VALUES ('$ma_khoa', '$ten_khoa')";
            

            if ($conn->query($sql_khoa) === TRUE ) {
                header("Location:manage_faculty.php");
                exit();
            } else {
                echo "Error: " . $sql_khoa . "<br>" . $conn->error;
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
            <h2>Thêm khoa</h2>
        </div>

        <form action="" class="add-form" method="post">
            <div class="input-form">
                <input type="text" id="ma_khoa" name="ma_khoa" placeholder="Mã khoa" required>
                <input type="text" id="ten_khoa" name="ten_khoa" placeholder="Tên khoa" required>
            </div>
            <br>
            <button type="submit">Nhập</button>
        </form>

    </div>       
        
    <?php include('footer.php'); ?>  

</body>
</html>
