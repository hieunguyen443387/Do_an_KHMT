<?php
    include('config.php'); 
    session_start();

    if (isset($_GET['ma_phong'])) {
        $ma_phong = $_GET['ma_phong'];

        // Sử dụng prepared statement để tránh SQL injection
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ma_phong = $_POST['ma_phong'];
            $suc_chua = intval($_POST['suc_chua']); // Chuyển đổi thành số nguyên để tránh lỗi

            // Cập nhật dữ liệu vào bảng với prepared statement
            $stmt = $conn->prepare("UPDATE phongthi SET suc_chua = ? WHERE ma_phong = ?");
            $stmt->bind_param("is", $suc_chua, $ma_phong); // "i" cho integer, "s" cho string

            if ($stmt->execute()) {
                header("Location: manage_class.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        // Lấy thông tin phòng thi từ cơ sở dữ liệu với prepared statement
        $stmt_phong_thi = $conn->prepare("SELECT suc_chua FROM phongthi WHERE ma_phong = ?");
        $stmt_phong_thi->bind_param("s", $ma_phong);
        $stmt_phong_thi->execute();
        $result_phong_thi = $stmt_phong_thi->get_result();

        if ($result_phong_thi->num_rows > 0) {
            $row = $result_phong_thi->fetch_assoc();
            $suc_chua = $row["suc_chua"];
        } else {
            echo "Phòng thi không tồn tại.";
        }

        $stmt_phong_thi->close();
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
            <h2>Cập nhật phòng thi: <?php echo htmlspecialchars($ma_phong); ?></h2>
        </div>

        <form action="" class="add-form" method="post">
            <div class="input-form">
                <input type="number" id="suc_chua" name="suc_chua" placeholder="Sức chứa" class="name" min="1" max="50" step="1" value="<?php echo htmlspecialchars($suc_chua); ?>" required>
            </div>
            <br>
            <button type="submit">Cập nhật</button>
        </form>

    </div>       

    <?php include('footer.php'); ?>

</body>
</html>