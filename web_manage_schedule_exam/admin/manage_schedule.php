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
            <!-- Thiết lập ngày đăng kí và hạn đăng kí -->
        <div class="registration-config">
            <h3>Thiết lập thời gian đăng ký lịch thi</h3>
            <form action="" method="post" class="date-form">
                <label for="start">Ngày bắt đầu:</label>
                <input type="date" id="start" name="ngay_bat_dau" required>

                <label for="end">Ngày kết thúc:</label>
                <input type="date" id="end" name="ngay_ket_thuc" required>

                <button type="submit" name="luu_cauhinh">Lưu</button>
            </form>

            <?php
                if (isset($_POST['luu_cauhinh'])) {
                    $ngay_bat_dau = $_POST['ngay_bat_dau'];
                    $ngay_ket_thuc = $_POST['ngay_ket_thuc'];

                    if ($ngay_bat_dau <= $ngay_ket_thuc) {
                        $stmt = $conn->prepare("INSERT INTO cauhinh_dangky (ngay_bat_dau, ngay_ket_thuc) VALUES (?, ?)");
                        $stmt->bind_param("ss", $ngay_bat_dau, $ngay_ket_thuc);
                        if ($stmt->execute()) {
                            echo "<p style='color:green;'>Đã lưu cấu hình thời gian đăng ký.</p>";
                        } else {
                            echo "<p style='color:red;'>Có lỗi xảy ra.</p>";
                        }
                        $stmt->close();
                    } else {
                        echo "<p style='color:red;'>Ngày bắt đầu không được sau ngày kết thúc.</p>";
                    }
                }

                // Hiển thị cấu hình hiện tại
                $result = $conn->query("SELECT * FROM cauhinh_dangky ORDER BY id DESC LIMIT 1");
                if ($row = $result->fetch_assoc()) {
                    echo "<p> Thời gian đăng ký hiện tại: <strong>" . $row['ngay_bat_dau'] . "</strong> đến <strong>" . $row['ngay_ket_thuc'] . "</strong></p>";
                }
            ?>
        </div>
            <h3>Danh sách học phần:</h3>
            <div class="button-group">
                <a id="add-button" href="add_schedule.php">Thêm lịch thi</a>
                
                <div class="search">
                    <input type="text" id="search-input" onkeyup="searchFunction()" placeholder="Nhập mã học phần hoặc tên học phần...">
                    <button id="search-button" onclick="searchFunction()"><i class="fa-solid fa-magnifying-glass"></i></button>          
                </div>

               
            </div>
            <table class="crud-table">
                <thead>
                    <tr>
                        <th>Mã MH</th>
                        <th>Tên môn học</th>
                        <th>Lịch thi</th>
                        <th>Cán bộ coi thi</th>
                        <th>Đã đăng kí</th>
                        <th>Xem</th>
                        <th>Sửa</th>
                    </tr>
                </thead>
                <tbody>

                    <?php         
                        require "limit_page.php";   
                        
                        $sql_lich_thi = "SELECT * FROM lichthi ORDER BY ma_hoc_phan ASC LIMIT $limit OFFSET $offset";
                        $result_lich_thi = $conn->query($sql_lich_thi);
                        
                        if ($result_lich_thi->num_rows > 0) {
                            $stt = $offset + 1;
                            while($row = $result_lich_thi->fetch_assoc()) {
                                $ma_phong = $row["ma_phong"];
                                $ma_lich_thi = $row["ma_lich_thi"];
                                $ma_hoc_phan = $row["ma_hoc_phan"];
                                $mgv = $row["mgv"];
                                $ngay_thi = $row["ngay_thi"];
                                $part = (explode("-",$ngay_thi));
                                $ngay_thi_update = $part[2] . "-" . $part[1]. "-" . $part[0];
                                $gio_ket_thuc = $row["gio_ket_thuc"];
                                $gio_bat_dau = $row["gio_bat_dau"];
                                echo '<tr>';
                                echo '<td>' . $ma_hoc_phan . '</td>';
                                $sql_hoc_phan = "SELECT * FROM hocphan where ma_hoc_phan = '$ma_hoc_phan'";
                                $result_hoc_phan = $conn->query($sql_hoc_phan);
                                
                                if ($result_hoc_phan->num_rows > 0) {
                                    while($row = $result_hoc_phan->fetch_assoc()) {
                                        echo '<td>' . $row["ten_hoc_phan"] . '</td>';
                                    }
                                }

                                echo '<td>' . $ngay_thi_update . ' từ ' . $gio_bat_dau . ' - ' . $gio_ket_thuc . ', Ph '. $ma_phong .'</td>';

                                $sql_giang_vien = "SELECT * FROM giangvien where mgv = '$mgv'";
                                $result_giang_vien = $conn->query($sql_giang_vien);
                                
                                if ($result_giang_vien->num_rows > 0) {
                                    $row = $result_giang_vien->fetch_assoc();
                                    echo '<td>' . $row["ho_dem"] . ' ' . $row["ten"] . '</td>';
                                    
                                }

                                //Đếm số lượng sinh viên đã đăny kí lịch thi
                                $sql = "SELECT COUNT(*) AS so_luong FROM dangkythi WHERE ma_lich_thi = '$ma_lich_thi'";
                                $result = $conn->query($sql);
                            
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    $so_luong = $row['so_luong'];
                                } else {
                                    $so_luong = 0; 
                                }
                                
                                echo '<td class="remain" style="color: red;">' . $so_luong . '</td>';
                                echo '<td id="view-icon"><a href="view_student_list.php?ma_lich_thi=' . $ma_lich_thi . '"><i class="fa-regular fa-eye"></i></a></td>';
                                echo '<td id="update-icon"><a href="update_schedule.php?ma_lich_thi=' . $ma_lich_thi . '"><i class="fa-solid fa-pen-to-square"></i></a></td>';
                                echo '</tr>';  
                            }
                        } else {
                            echo "Chưa có lịch thi";
                        }                        
                    ?>

                </tbody>

            </table>
            <?php 
                $sql_trang = "SELECT COUNT(*) AS total FROM hocphan ";
                $result_trang = $conn->query($sql_trang);
                require "pagination.php"; 
            ?>  
        </div> 
    </div>       
        
    <?php include('footer.php'); ?>  

</body>
</html>