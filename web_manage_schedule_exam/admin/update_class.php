<?php
    include('config.php'); 
    session_start();
    if (isset($_GET['ma_phong'])) {
        $ma_phong = $_GET['ma_phong'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ma_phong = $_POST['ma_phong'];
            $suc_chua = $_POST['suc_chua'];   
    
            // Cập nhật dữ liệu vào bảng
            $sql_phong_thi = "UPDATE phongthi set suc_chua = '$suc_chua'";         

            if ($conn->query($sql_phong_thi) === TRUE ) {
                header("Location:manage_class.php");
                exit();
            } else {
                echo "Error: " . $sql_phong_thi . "<br>" . $conn->error;
            }
            
        }
    }

    $sql_phong_thi = "SELECT * FROM phongthi WHERE ma_phong = '$ma_phong'";
    $result_phong_thi = $conn->query($sql_phong_thi);
    if ($result_phong_thi->num_rows > 0) {
        $stt = 1;
        while($row = $result_phong_thi->fetch_assoc()) {
            $suc_chua = $row["suc_chua"];
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
            <h2>Cập nhật phòng thi: <?php echo $ma_phong; ?></h2>
        </div>

        <form action="" class="add-form" method="post">
            <div class="input-form">
                <input type="number" id="suc_chua" name="suc_chua" placeholder="Sức chứa" class="name"  min="1" max="50" step="1"  value = "<?php echo $suc_chua; ?>" required>
            </div>
            <br>
            <button type="submit">Cập nhật</button>
        </form>

    </div>       
        
    <?php include('footer.php'); ?>

</body>
</html>
