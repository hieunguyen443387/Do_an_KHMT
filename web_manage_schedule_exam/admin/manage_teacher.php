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
            <h3>Danh sách sinh viên:</h3>
            <div class="button-group">
                <a id="add-button" href="add_teacher.php">Thêm giảng viên</a>
                <form action="import.php" method="post" enctype="multipart/form-data" class="upload-form">
                    <label for="file-upload" class="custom-file-upload">
                        Choose File
                    </label>
                    <button type="submit" id="import-button" name="import-teacher-button">Upload</button>
                    <input id="file-upload" type="file" name="excel_file" accept=".xls,.xlsx" required>
                </form>
                <div class="search">
                    <input type="text" id="search-input" placeholder="Nhập mã giảng viên hoặc tên..." onkeyup="searchFunction()">
                    <button id="search-button" onclick="searchFunction()"><i class="fa-solid fa-magnifying-glass"></i></button>          
                </div>
               
            </div>
            <table class="crud-table">
                <thead>
                    <tr>
                        <th>Chọn</th>
                        <th>STT</th>
                        <th>Mã giảng viên</th>
                        <th>Tên giảng viên</th>
                        <th>Ngày sinh</th>
                        <th>Khoa</th>
                        <th>Giới tính</th>
                        <th>Sửa</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <form action="delete.php" method="post">

                    <?php         
                        require "limit_page.php";   
                        $sql_giang_vien = "SELECT * FROM giangvien LIMIT $limit OFFSET $offset";           
                        $result_giang_vien = $conn->query($sql_giang_vien);
                        if ($result_giang_vien->num_rows > 0) {
                            $stt = $offset + 1;
                            while($row = $result_giang_vien->fetch_assoc()) {
                                $mgv = $row["mgv"];
                                $ngay_sinh = $row['ngay_sinh'];
                                $part = (explode("-",$ngay_sinh));
                                $ngay_sinh_update = $part[2] . "-" . $part[1]. "-" . $part[0];
                                echo '<tr>';
                                echo '<td><input type="checkbox" name="select_all[]" value="' . $mgv . '"></td>';
                                echo '<td>' . $stt++ . '</td>';
                                echo '<td>' . $mgv . '</td>';
                                echo '<td>' . $row["ho_dem"] . " " . $row["ten"] . '</td>';
                                echo '<td>' . $ngay_sinh_update . '</td>';
                                echo '<td>' . $row["khoa"] . '</td>';
                                echo '<td>' . $row["gioi_tinh"] . '</td>';
                                echo '<td id="update-icon"><a href="update_teacher.php?mgv=' . $mgv . '"><i class="fa-solid fa-pen-to-square"></i></a></td>';
                                echo '<td id="delete-icon"><a href="delete.php?mgv=' . $mgv . '"><i class="fa-solid fa-trash-can"></i></a></td>';   
                                echo '</tr>';  
                            }
                        } else {
                            echo "Chưa có giảng viên";
                        }                        
                    ?>

                </tbody>

            </table>
            <?php
                if ($result_giang_vien->num_rows > 0) {
                    echo '<div class="selected-box">
                            <span> Chọn tất cả <input type="checkbox" onClick="toggle(this)" /></span>
                                <button type="submit" name="delete_multiple_teacher"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </div>';
                }
            ?>
            <?php 
                $sql_trang = "SELECT COUNT(*) AS total FROM giangvien ";
                $result_trang = $conn->query($sql_trang);
                require "pagination.php"; 
            ?>  
        </div> 
    </div>       
        
    <?php include('footer.php'); ?>  

</body>
</html>