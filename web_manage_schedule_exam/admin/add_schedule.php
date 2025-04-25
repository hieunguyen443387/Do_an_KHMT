<?php 
    include('config.php'); 
    session_start();
    // Lấy cấu hình lịch thi
    $sql_cauhinh = "SELECT ngay_bat_dau, ngay_ket_thuc FROM cauhinh_dangky LIMIT 1";
    $stmt_cauhinh = $conn->prepare($sql_cauhinh);
    $stmt_cauhinh->execute();
    $result_cauhinh = $stmt_cauhinh->get_result();

    if ($result_cauhinh && $result_cauhinh->num_rows > 0) {
        $row_cauhinh = $result_cauhinh->fetch_assoc();
        $ngay_bat_dau = $row_cauhinh['ngay_bat_dau'];
        $ngay_ket_thuc = $row_cauhinh['ngay_ket_thuc'];
    } else {
        $ngay_bat_dau = '';
        $ngay_ket_thuc = '';
    }

    $stmt_cauhinh->close();


    $id_admin = $_SESSION['id_admin'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $ma_lich_thi = $_POST['ma_lich_thi'];
        $ma_hoc_phan = $_POST['ma_hoc_phan'];
        $ngay_thi = $_POST['ngay_thi'];
        $gio_bat_dau = $_POST['gio_bat_dau'];
        $gio_ket_thuc = $_POST['gio_ket_thuc'];
        $ma_phong = $_POST['ma_phong'];
        $mgv = $_POST['mgv'];

        // Kiểm tra lịch thi trùng
        $sql_check = "SELECT * FROM lichthi 
            WHERE ma_lich_thi = ? 
            OR (ngay_thi = ? AND ma_phong = ? AND mgv = ? AND ? BETWEEN gio_bat_dau AND gio_ket_thuc)";
        $stmt = $conn->prepare($sql_check);
        $stmt->bind_param("sssss", $ma_lich_thi, $ngay_thi, $ma_phong, $mgv, $gio_bat_dau);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0 ) {
            while($row = $result->fetch_assoc()) {
                if ($gio_bat_dau == $row['gio_bat_dau'] && $ngay_thi == $row['ngay_thi'] && $ma_phong == $row['ma_phong']) {
                    echo '<div class="alert">Lịch thi đã tồn tại!</div>';
                    exit();
                }
            }
            echo '<div class="alert">Lịch thi đã tồn tại!</div>';
        } else {
            // Chèn dữ liệu vào bảng lichthi
            $sql_insert = "INSERT INTO lichthi (ma_lich_thi, ma_hoc_phan, ngay_thi, gio_bat_dau, gio_ket_thuc, ma_phong, mgv) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("sssssss", $ma_lich_thi, $ma_hoc_phan, $ngay_thi, $gio_bat_dau, $gio_ket_thuc, $ma_phong, $mgv);

            if ($stmt_insert->execute()) {
                header("Location: manage_schedule.php");
                exit();
            } else {
                echo "Lỗi: " . $stmt_insert->error;
            }
            $stmt_insert->close();
        }

        $stmt->close();
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
            <h2>Thêm lịch thi</h2>
        </div>

        <form action="" class="add-form" method="post">
            <div class="input-form">
                <input type="text" id="ma_lich_thi" name="ma_lich_thi" placeholder="Mã lịch thi" required>
                <br>
                <br>
                <select name="ma_hoc_phan" id="ma_hoc_phan"class="filter">
                    <option value="">Chọn học phần</option>
                    <?php
                        $sql_hoc_phan = "SELECT * FROM hocphan";
                        $result_hoc_phan = $conn->query($sql_hoc_phan);

                        if ($result_hoc_phan->num_rows > 0) {
                            while($row = $result_hoc_phan->fetch_assoc()) {
                                echo '<option value="' . $row['ma_hoc_phan'] . '">' . $row['ten_hoc_phan'] . '</option>';
                            }
                        }
                    ?>
                </select>
                <br>
                <br>
                <select name="ma_phong" id="ma_phong" class="filter">
                    <option value="">Chọn phòng thi</option>
                    <?php
                        $sql_phong_thi = "SELECT * FROM phongthi";
                        $result_phong_thi = $conn->query($sql_phong_thi);

                        if ($result_phong_thi->num_rows > 0) {
                            while($row = $result_phong_thi->fetch_assoc()) {
                                echo '<option value="' . $row['ma_phong'] . '">' . $row['ma_phong'] . '</option>';
                            }
                        }
                    ?>
                </select>
                <br>
                <br>
                <select name="mgv" id="mgv"class="filter">
                    <option value="">Chọn cán bộ coi thi</option>
                    <?php
                        $sql_giang_vien = "SELECT * FROM giangvien";           
                        $result_giang_vien = $conn->query($sql_giang_vien);
                        if ($result_giang_vien->num_rows > 0) {
                            while($row = $result_giang_vien->fetch_assoc()) {
                                echo '<option value="' . $row['mgv'] . '">' . $row['ho_dem'] . ' ' . $row['ten'] .'</option>';
                            }
                        }
                    ?>
                </select>
                <input type="date" id="ngay_thi" name="ngay_thi" required value="<?php echo $ngay_bat_dau; ?>" min="<?php echo $ngay_bat_dau; ?>" max="<?php echo $ngay_ket_thuc; ?>">

            </div>
            <div class="exam-time">
                <input type="time" id="gio_bat_dau" name="gio_bat_dau" placeholder="Giờ bắt đầu" required oninput="exam_time()">
                <input type="time" id="gio_ket_thuc" name="gio_ket_thuc" placeholder="Giờ kết thúc" required>
            </div>
            <br>
            <button type="submit">Nhập</button>
        </form>

    </div>       
        
    <?php include('footer.php'); ?>  

    <script>
        function exam_time() {
            let gioBatDau = document.querySelector('#gio_bat_dau').value; 
            if (gioBatDau) {
                let [hours, minutes] = gioBatDau.split(':').map(Number); 
                let totalMinutes = hours * 60 + minutes + 50; 
                let newHours = Math.floor(totalMinutes / 60); 
                let newMinutes = totalMinutes % 60; 

                let gioKetThuc = 
                    String(newHours).padStart(2, '0') + ':' + 
                    String(newMinutes).padStart(2, '0');

                document.querySelector('#gio_ket_thuc').value = gioKetThuc; 
            }
        }

    </script>

</body>
</html>
