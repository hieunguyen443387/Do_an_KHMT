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

        // Kiểm tra học phần đã tồn tại chưa
        $stmt_check = $conn->prepare("SELECT * FROM hocphan WHERE ma_hoc_phan = ?");
        $stmt_check->bind_param("s", $ma_hoc_phan);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            echo '<div class="alert">Mã học phần đã tồn tại!</div>';
        } else {
            // Thêm học phần mới
            $stmt_insert = $conn->prepare("INSERT INTO hocphan (ma_hoc_phan, ten_hoc_phan, so_tin_chi) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("ssi", $ma_hoc_phan, $ten_hoc_phan, $so_tin_chi);

            if ($stmt_insert->execute()) {
                header("Location: manage_course.php");
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
            <h2>Thêm học phần</h2>
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
