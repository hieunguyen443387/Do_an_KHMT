<?php
    include('config.php'); 
    session_start();
    if (isset($_GET['mgv'])) {
        $mgv = $_GET['mgv'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ho_dem = $_POST['ho_dem'];
            $ten = $_POST['ten'];
            $khoa = $_POST['khoa'];
            $ngay_sinh = $_POST['ngay_sinh'];
            $gioi_tinh = $_POST['gioi_tinh'];  

            // Use prepared statement to prevent SQL injection
            $sql_giang_vien = "UPDATE giangvien SET ho_dem = ?, ten = ?, khoa = ?, ngay_sinh = ?, gioi_tinh = ? WHERE mgv = ?";
            if ($stmt = $conn->prepare($sql_giang_vien)) {
                // Bind the parameters to the prepared statement
                $stmt->bind_param("ssssss", $ho_dem, $ten, $khoa, $ngay_sinh, $gioi_tinh, $mgv);

                // Execute the statement
                if ($stmt->execute()) {
                    header("Location: manage_teacher.php");
                    exit();
                } else {
                    echo "Error: " . $stmt->error;
                }

                $stmt->close(); // Close the statement after execution
            } else {
                echo "Error preparing statement: " . $conn->error;
            }
        }
    }

    // Use prepared statement to fetch teacher data
    $sql_giang_vien = "SELECT * FROM giangvien WHERE mgv = ?";
    if ($stmt = $conn->prepare($sql_giang_vien)) {
        // Bind the 'mgv' parameter
        $stmt->bind_param("s", $mgv);  
        $stmt->execute();
        $result_giang_vien = $stmt->get_result();
        if ($result_giang_vien->num_rows > 0) {
            while ($row = $result_giang_vien->fetch_assoc()) {
                $ho_dem = $row["ho_dem"];
                $ten = $row["ten"];
                $khoa = $row["khoa"];
                $ngay_sinh = $row["ngay_sinh"];
                $gioi_tinh = $row["gioi_tinh"];
            }
        }
        $stmt->close(); // Close the statement after fetching data
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
            <h2>Cập nhật tài khoản giảng viên: <?php echo $mgv; ?></h2>
        </div>

        <form action="" class="add-form" method="post">
            <div class="input-form">
                <input type="text" id="ho_dem" name="ho_dem" placeholder="Họ đệm" class="name" required value = "<?php echo $ho_dem; ?>">
                <input type="text" id="ten" name="ten" placeholder="Tên" class="name" required value = "<?php echo $ten; ?>">
                <input type="text" id="khoa" name="khoa" placeholder="Khoa" required value = "<?php echo $khoa; ?>">
                <input type="date" id="ngay_sinh" name="ngay_sinh" value = "<?php echo $ngay_sinh; ?>">
            </div>
            <div class="gender">
                <input type="radio" id="male" name="gioi_tinh" value="Nam" <?php if ($gioi_tinh == "Nam") echo 'checked'; ?>>
                <label for="male" >Nam</label>
                <input type="radio" id="female" name="gioi_tinh" value="Nữ" <?php if ($gioi_tinh == "Nữ") echo 'checked'; ?>>
                <label for="female">Nữ</label><br>
            </div>
            <br>
            <button type="submit">Cập nhật</button>
        </form>

    </div>       
        
    <?php include('footer.php'); ?>

</body>
</html>