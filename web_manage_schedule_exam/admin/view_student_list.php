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
                    $sql_lich_thi = "SELECT * FROM lichthi WHERE ma_lich_thi = '$ma_lich_thi'";
                    $result_lich_thi = $conn->query($sql_lich_thi);
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
                        $sql_hoc_phan = "SELECT * FROM hocphan WHERE ma_hoc_phan = '$ma_hoc_phan'";
                        $result_hoc_phan = $conn->query($sql_hoc_phan);
                        if ($result_hoc_phan->num_rows > 0) {
                            $row = $result_hoc_phan->fetch_assoc();
                            echo ' học phần ' . $row["ten_hoc_phan"];
                        }
                    }
                ?>
            :</h3>
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

                        $sql_dang_ki_thi = "SELECT * FROM dangkythi WHERE ma_lich_thi = '$ma_lich_thi'";
                        $result_dang_ki_thi = $conn->query($sql_dang_ki_thi);
                        if ($result_dang_ki_thi->num_rows > 0) {
                            $stt = 1;
                            while($row = $result_dang_ki_thi->fetch_assoc()) {
                                $msv = $row["msv"];
                                echo '<tr>';
                                echo '<td>' . $stt++ . '</td>';
                                echo '<td>' . $msv . '</td>';
                                $sql_sinh_vien = "SELECT * FROM sinhvien WHERE msv = '$msv'";
                                $result_sinh_vien = $conn->query($sql_sinh_vien);
                                if ($result_sinh_vien->num_rows > 0) {
                                    $stt = 1;
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
                                
                                echo '</tr>';  
                            }
                        } else {
                            echo "Chưa có lịch thi";
                        }                        
                    ?>

                </tbody>

            </table>
        </div> 
    </div>       
        
    <?php include('footer.php'); ?>  

</body>
</html>