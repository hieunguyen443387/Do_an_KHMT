<?php
    include('config.php'); 
    if (isset($_GET['ma_lich_thi'])) {
        $ma_lich_thi = $_GET['ma_lich_thi'];
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Trang quản lý lịch thi</title>
</head>
<body> 

<?php include('header.php'); ?>

    <div class="container">
        <?php include('tag.php'); ?>

        <div class="crud">
            <h3>Danh sách sinh viên lịch thi 
                <?php 
                    $sql_lich_thi = "SELECT * FROM lichthi WHERE ma_lich_thi = ?";
                    if ($stmt = $conn->prepare($sql_lich_thi)) {
                        $stmt->bind_param("s", $ma_lich_thi);
                        $stmt->execute();
                        $result_lich_thi = $stmt->get_result();
                        if ($result_lich_thi->num_rows > 0) {
                            $row = $result_lich_thi->fetch_assoc();
                            $ma_phong = $row["ma_phong"];
                            $ma_lich_thi = $row["ma_lich_thi"];
                            $ma_hoc_phan = $row["ma_hoc_phan"];
                            $ngay_thi = $row["ngay_thi"];
                            $part = (explode("-",$ngay_thi));
                            $ngay_thi_update = $part[2] . "-" . $part[1]. "-" . $part[0];
                            $gio_ket_thuc = $row["gio_ket_thuc"];
                            $gio_bat_dau = $row["gio_bat_dau"];
                            echo  $ngay_thi_update . ' từ ' . $gio_bat_dau . ' - ' . $gio_ket_thuc . ', Ph '. $ma_phong;

                            //Lấy tên học phần
                            $sql_hoc_phan = "SELECT * FROM hocphan WHERE ma_hoc_phan = ?";
                            if ($stmt_hoc_phan = $conn->prepare($sql_hoc_phan)) {
                                $stmt_hoc_phan->bind_param("s", $ma_hoc_phan);
                                $stmt_hoc_phan->execute();
                                $result_hoc_phan = $stmt_hoc_phan->get_result();
                                if ($result_hoc_phan->num_rows > 0) {
                                    $row = $result_hoc_phan->fetch_assoc();
                                    echo ', học phần ' . $row["ten_hoc_phan"];
                                }
                                $stmt_hoc_phan->close();
                            }
                        }
                        $stmt->close();
                    }
                ?>
            :</h3>
            <br>
            <form action="export.php?ma_lich_thi=<?php echo $ma_lich_thi; ?>" method="post">
                <button type="submit" id="export-button" name="export-student-button"><i class="fa-solid fa-file-excel"></i>Xuất Excel</button>
            </form>
            <table class="crud-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã sinh viên</th>
                        <th>Họ tên</th>
                        <th>Lớp</th>
                        <th>Khoa</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                    </tr>
                </thead>
                <tbody>
                    <?php         
                        require "limit_page.php";   

                        $sql_dang_ki_thi = "SELECT * FROM dangkythi WHERE ma_lich_thi = ?";
                        if ($stmt_dang_ki_thi = $conn->prepare($sql_dang_ki_thi)) {
                            $stmt_dang_ki_thi->bind_param("s", $ma_lich_thi);
                            $stmt_dang_ki_thi->execute();
                            $result_dang_ki_thi = $stmt_dang_ki_thi->get_result();
                            if ($result_dang_ki_thi->num_rows > 0) {
                                $stt = 1;
                                while($row = $result_dang_ki_thi->fetch_assoc()) {
                                    $msv = $row["msv"];
                                    echo '<tr>';
                                    echo '<td>' . $stt++ . '</td>';
                                    echo '<td>' . $msv . '</td>';

                                    // Use prepared statement for fetching student data
                                    $sql_sinh_vien = "SELECT * FROM sinhvien WHERE msv = ?";
                                    if ($stmt_sinh_vien = $conn->prepare($sql_sinh_vien)) {
                                        $stmt_sinh_vien->bind_param("s", $msv);
                                        $stmt_sinh_vien->execute();
                                        $result_sinh_vien = $stmt_sinh_vien->get_result();
                                        if ($result_sinh_vien->num_rows > 0) {
                                            while($row = $result_sinh_vien->fetch_assoc()) {
                                                $ho_dem = $row["ho_dem"];
                                                $ten = $row["ten"];
                                                $lop = $row["lop"];
                                                $khoa = $row["khoa"];
                                                $ngay_sinh = $row["ngay_sinh"];
                                                $part = (explode("-",$ngay_sinh));
                                                $ngay_sinh_update = $part[2] . "-" . $part[1]. "-" . $part[0];
                                                $gioi_tinh = $row["gioi_tinh"];
                                                echo '<td>' . $ho_dem . ' ' . $ten . '</td>';
                                                echo '<td>' . $lop . '</td>';
                                                echo '<td>' . $khoa . '</td>';
                                                echo '<td>' . $ngay_sinh_update . '</td>';
                                                echo '<td>' . $gioi_tinh . '</td>';
                                            }
                                        }
                                        $stmt_sinh_vien->close();
                                    }
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr>
                                        <td colspan="10" style="text-align: center; padding: 20px; font-style: italic; color: #777;">
                                            <div style="display: inline-block; padding: 10px 20px; border-radius: 10px;">
                                                Chưa có sinh viên đăng ký lịch thi 
                                            </div>
                                        </td>
                                    </tr>';
                            }   
                            $stmt_dang_ki_thi->close(); 
                        }
                    ?>

                </tbody>
            </table>
        </div> 
    </div>       
        
    <?php include('footer.php'); ?>  

</body>
</html>