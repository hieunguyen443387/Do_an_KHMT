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
            <h3>Danh sách học phần:</h3>
            <div class="button-group">
                <a id="add-button" href="add_schedule.php">Thêm lịch thi</a>
                <form action="import.php" method="post" enctype="multipart/form-data" class="upload-form">
                    <label for="file-upload" class="custom-file-upload">
                        Choose File
                    </label>
                    <button type="submit" id="import-button" name="import-course-button">Upload</button>
                    <input id="file-upload" type="file" name="excel_file" accept=".xls,.xlsx" required>
                </form>
                <div class="search">
                    <input type="text" id="search-input" onkeyup="searchFunction()" placeholder="Nhập mã học phần hoặc tên học phần...">
                    <button id="search-button"><i class="fa-solid fa-magnifying-glass"></i></button>          
                </div>

               
            </div>
            <table class="crud-table">
                <thead>
                    <tr>
                        <th>Mã MH</th>
                        <th>Tên môn học</th>
                        <th>Lịch thi</th>
                        <th>Số lượng</th>
                        <th>Còn lại</th>
                        <th>Xem</th>
                        <th>Sửa</th>
                    </tr>
                </thead>
                <tbody>

                    <?php         
                        require "limit_page.php";   
                        
                        $sql_lich_thi = "SELECT * FROM lichthi LIMIT $limit OFFSET $offset";
                        $result_lich_thi = $conn->query($sql_lich_thi);
                        
                        if ($result_lich_thi->num_rows > 0) {
                            $stt = $offset + 1;
                            while($row = $result_lich_thi->fetch_assoc()) {
                                $ma_phong = $row["ma_phong"];
                                $ma_lich_thi = $row["ma_lich_thi"];
                                $ma_hoc_phan = $row["ma_hoc_phan"];
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

                                $sql_phong_thi = "SELECT * FROM phongthi";
                                $result_phong_thi = $conn->query($sql_phong_thi);
                                
                                if ($result_phong_thi->num_rows > 0) {
                                    while($row = $result_phong_thi->fetch_assoc()) {
                                        echo '<td>' . $row["suc_chua"] . '</td>';
                                    }
                                }
                                echo '<td id="view-icon"><a href=""><i class="fa-regular fa-eye"></i></a></td>';
                                echo '<td id="update-icon"><a href="update_schedule.php?ma_lich_thi=' . $ma_lich_thi . '"><i class="fa-solid fa-pen-to-square"></i></a></td>';
                                echo '</tr>';  
                            }
                        } else {
                            echo "Chưa có lịch thi";
                        }                        
                    ?>

                </tbody>
                </form>

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