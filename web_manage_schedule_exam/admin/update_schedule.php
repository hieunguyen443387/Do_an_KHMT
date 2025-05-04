<?php 
    include('config.php'); 
    session_start();
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
    
    if (!isset($_SESSION['id_admin'])) {
        header("Location: ../home_admin/home_admin.html");
        exit();
    }

    $id_admin = $_SESSION['id_admin'];

    if (isset($_GET['ma_lich_thi'])) {
        $ma_lich_thi = $_GET['ma_lich_thi'];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ma_hoc_phan = $_POST['ma_hoc_phan'];
            $ngay_thi = $_POST['ngay_thi'];
            $gio_bat_dau = $_POST['gio_bat_dau'];
            $gio_ket_thuc = $_POST['gio_ket_thuc'];
            $ma_phong = $_POST['ma_phong'];
            $mgv = $_POST['mgv'];

            // Sử dụng prepared statement để tránh SQL injection
            $stmt = $conn->prepare("SELECT * FROM lichthi 
                                    WHERE (ma_lich_thi <> ? 
                                    AND ngay_thi = ? 
                                    AND ma_phong = ?
                                    AND mgv = ?
                                    AND (? BETWEEN gio_bat_dau AND gio_ket_thuc))");

            $stmt->bind_param("issss", $ma_lich_thi, $ngay_thi, $ma_phong, $mgv, $gio_bat_dau);
            $stmt->execute();
            $result_lich_thi = $stmt->get_result();

            if ($result_lich_thi->num_rows > 0) {
                echo "Đã tồn tại";
            } else {
                // Cập nhật lịch thi sử dụng prepared statement
                $stmt_update = $conn->prepare("UPDATE lichthi 
                                              SET ma_hoc_phan = ?, ngay_thi = ?, gio_bat_dau = ?, gio_ket_thuc = ?, ma_phong = ?, mgv = ? 
                                              WHERE ma_lich_thi = ?");
                $stmt_update->bind_param("ssssssi", $ma_hoc_phan, $ngay_thi, $gio_bat_dau, $gio_ket_thuc, $ma_phong, $mgv, $ma_lich_thi);

                if ($stmt_update->execute()) {
                    header("Location:manage_schedule.php");
                    exit();
                } else {
                    echo "Error: " . $stmt_update->error;
                }
            }

            $stmt->close();
        }
    }

    // Lấy thông tin lịch thi
    $stmt_lich_thi = $conn->prepare("SELECT * FROM lichthi WHERE ma_lich_thi = ?");
    $stmt_lich_thi->bind_param("i", $ma_lich_thi);
    $stmt_lich_thi->execute();
    $result_lich_thi = $stmt_lich_thi->get_result();
    
    if ($result_lich_thi->num_rows > 0) {
        $row = $result_lich_thi->fetch_assoc();
        $ma_phong = $row["ma_phong"];
        $ma_lich_thi = $row["ma_lich_thi"];
        $ma_hoc_phan = $row["ma_hoc_phan"];
        $mgv = $row["mgv"];
        $ngay_thi = $row["ngay_thi"];
        $part = (explode("-", $ngay_thi));
        $ngay_thi_update = $part[2] . "-" . $part[1] . "-" . $part[0];
        $gio_ket_thuc = $row["gio_ket_thuc"];
        $gio_bat_dau = $row["gio_bat_dau"];
    }   

    $stmt_lich_thi->close();
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
            <h2>Cập nhật lịch thi</h2>
        </div>

        <form action="" class="add-form" method="post">
            <div class="input-form">
                <br><br>
                <select name="ma_hoc_phan" id="ma_hoc_phan" class="filter">
                    <option value="">Chọn học phần</option>
                    <?php
                        $stmt_hoc_phan = $conn->prepare("SELECT * FROM hocphan");
                        $stmt_hoc_phan->execute();
                        $result_hoc_phan = $stmt_hoc_phan->get_result();

                        while($row = $result_hoc_phan->fetch_assoc()) {
                            echo '<option value="' . $row['ma_hoc_phan'] . '"';
                            if ($row['ma_hoc_phan'] == $ma_hoc_phan) {
                                echo ' selected';
                            }
                            echo '>' . htmlspecialchars($row['ten_hoc_phan']) . '</option>';
                        }
                    ?>
                </select>
                <br><br>

                <select name="ma_phong" id="ma_phong" class="filter">
                    <option value="">Chọn phòng thi</option>
                    <?php
                        $stmt_phong_thi = $conn->prepare("SELECT * FROM phongthi");
                        $stmt_phong_thi->execute();
                        $result_phong_thi = $stmt_phong_thi->get_result();

                        while($row = $result_phong_thi->fetch_assoc()) {
                            echo '<option value="' . $row['ma_phong'] . '"';
                            if ($row['ma_phong'] == $ma_phong) {
                                echo ' selected';
                            }
                            echo '>' . htmlspecialchars($row['ma_phong']) . '</option>';
                        }
                    ?>
                </select>
                <br><br>

                <select name="mgv" id="mgv" class="filter">
                    <option value="">Chọn cán bộ coi thi</option>
                    <?php
                        $stmt_giang_vien = $conn->prepare("SELECT * FROM giangvien");           
                        $stmt_giang_vien->execute();
                        $result_giang_vien = $stmt_giang_vien->get_result();

                        while($row = $result_giang_vien->fetch_assoc()) {
                            echo '<option value="' . $row['mgv'] . '"';
                            if ($row['mgv'] == $mgv) {
                                echo ' selected';
                            }
                            echo '>' . htmlspecialchars($row['ho_dem']) . ' ' . htmlspecialchars($row['ten']) . '</option>';
                        }
                    ?>
                </select>

                <input type="date" id="ngay_thi" name="ngay_thi" placeholder="Ngày thi" value="<?php echo htmlspecialchars($ngay_thi); ?>" min="<?php echo $ngay_bat_dau; ?>" max="<?php echo $ngay_ket_thuc; ?>" required>
            </div>

            <div class="exam-time">
                <input type="time" id="gio_bat_dau" name="gio_bat_dau" placeholder="Giờ bắt đầu" value="<?php echo htmlspecialchars($gio_bat_dau); ?>" required oninput="exam_time()">
                <input type="time" id="gio_ket_thuc" name="gio_ket_thuc" placeholder="Giờ kết thúc" value="<?php echo htmlspecialchars($gio_ket_thuc); ?>" required>
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