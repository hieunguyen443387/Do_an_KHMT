<?php
    include('config.php'); 
    session_start();

    if (isset($_GET['ma_hoc_phan'])) {
        $ma_hoc_phan = $_GET['ma_hoc_phan'];

        // Kiểm tra nếu dữ liệu được gửi đi từ form (POST)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy và làm sạch dữ liệu đầu vào
            $ma_hoc_phan = $_POST['ma_hoc_phan'];
            $ten_hoc_phan = $_POST['ten_hoc_phan'];
            $so_tin_chi = intval($_POST['so_tin_chi']);  // Đảm bảo so_tin_chi là số nguyên

            // Cập nhật dữ liệu vào bảng với prepared statement
            $stmt = $conn->prepare("UPDATE hocphan SET ten_hoc_phan = ?, so_tin_chi = ? WHERE ma_hoc_phan = ?");
            $stmt->bind_param("sis", $ten_hoc_phan, $so_tin_chi, $ma_hoc_phan);  // "s" cho string, "i" cho integer

            if ($stmt->execute()) {
                header("Location: manage_course.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        // Lấy thông tin học phần từ cơ sở dữ liệu với prepared statement
        $stmt_hoc_phan = $conn->prepare("SELECT ten_hoc_phan, so_tin_chi FROM hocphan WHERE ma_hoc_phan = ?");
        $stmt_hoc_phan->bind_param("s", $ma_hoc_phan);
        $stmt_hoc_phan->execute();
        $result_hoc_phan = $stmt_hoc_phan->get_result();

        if ($result_hoc_phan->num_rows > 0) {
            $row = $result_hoc_phan->fetch_assoc();
            $ten_hoc_phan = $row["ten_hoc_phan"];
            $so_tin_chi = $row["so_tin_chi"];
        } else {
            echo "Học phần không tồn tại.";
        }

        $stmt_hoc_phan->close();
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
            <h2>Cập nhật học phần: <?php echo htmlspecialchars($ma_hoc_phan); ?></h2>
        </div>

        <form action="" class="add-form" method="post">
            <div class="input-form">
                <input type="text" id="ten_hoc_phan" name="ten_hoc_phan" placeholder="Tên học phần" value="<?php echo htmlspecialchars($ten_hoc_phan); ?>" required>
                <input type="number" id="so_tin_chi" name="so_tin_chi" placeholder="Số tín chỉ" class="name" min="1" max="4" step="1" value="<?php echo htmlspecialchars($so_tin_chi); ?>" required>
            </div>
            <br>
            <button type="submit">Cập nhật</button>
        </form>

    </div>       

    <?php include('footer.php'); ?>

</body>
</html>