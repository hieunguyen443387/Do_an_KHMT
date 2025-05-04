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
                <a id="add-button" href="add_student.php">Thêm sinh viên</a>
                <form action="import.php" method="post" enctype="multipart/form-data" class="upload-form">
                    <label for="file-upload" class="custom-file-upload">
                        Choose File
                    </label>
                    <button type="submit" id="import-button" name="import-student-button">Upload</button>
                    <input id="file-upload" type="file" name="excel_file" accept=".xls,.xlsx" required>
                </form>
                <div class="search">
                    <input type="text" id="search-input" placeholder="Nhập mã sinh viên hoặc tên..." onkeyup="searchFunction()">
                    <button id="search-button" onclick="searchFunction()"><i class="fa-solid fa-magnifying-glass"></i></button>          
                </div>

               
            </div>
            <table class="crud-table">
                <thead>
                    <tr>
                        <th>Chọn</th>
                        <th>STT</th>
                        <th>Mã sinh viên</th>
                        <th>Tên sinh viên</th>
                        <th>Ngày sinh</th>
                        <th>Lớp</th>
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

                        // Truy vấn danh sách sinh viên bằng prepared statement
                        $stmt = $conn->prepare("SELECT * FROM sinhvien LIMIT ? OFFSET ?");
                        $stmt->bind_param("ii", $limit, $offset);  // "ii" cho 2 tham số kiểu int
                        $stmt->execute();
                        $result_sinh_vien = $stmt->get_result();

                        if ($result_sinh_vien->num_rows > 0) {
                            $stt = $offset + 1;
                            while($row = $result_sinh_vien->fetch_assoc()) {
                                // Escape dữ liệu đầu ra
                                $msv = htmlspecialchars($row["msv"]);
                                $ho_dem = htmlspecialchars($row["ho_dem"]);
                                $ten = htmlspecialchars($row["ten"]);
                                $lop = htmlspecialchars($row["lop"]);
                                $khoa = htmlspecialchars($row["khoa"]);
                                $gioi_tinh = htmlspecialchars($row["gioi_tinh"]);

                                // Định dạng lại ngày sinh
                                $ngay_sinh = htmlspecialchars($row['ngay_sinh']);
                                $part = explode("-", $ngay_sinh);
                                $ngay_sinh_update = $part[2] . "-" . $part[1] . "-" . $part[0];

                                echo '<tr>';
                                echo '<td><input type="checkbox" name="select_all[]" value="' . $msv . '"></td>';
                                echo '<td>' . $stt++ . '</td>';
                                echo '<td>' . $msv . '</td>';
                                echo '<td>' . $ho_dem . " " . $ten . '</td>';
                                echo '<td>' . $ngay_sinh_update . '</td>';
                                echo '<td>' . $lop . '</td>';
                                echo '<td>' . $khoa . '</td>';
                                echo '<td>' . $gioi_tinh . '</td>';
                                echo '<td id="update-icon"><a href="update_student.php?msv=' . urlencode($msv) . '"><i class="fa-solid fa-pen-to-square"></i></a></td>';
                                echo '<td id="delete-icon"><a href="delete.php?msv=' . urlencode($msv) . '"><i class="fa-solid fa-trash-can"></i></a></td>';   
                                echo '</tr>';  
                            }
                        } else {
                            echo '<tr>
                                <td colspan="10" style="text-align: center; padding: 20px; font-style: italic; color: #777;">
                                    <div style="display: inline-block; padding: 10px 20px; border-radius: 10px;">
                                        Chưa có sinh viên nào trong danh sách
                                    </div>
                                </td>
                            </tr>';
                        }

                        $stmt->close();
                    ?>

                </tbody>

            </table>
            <?php
                if ($result_sinh_vien->num_rows > 0) {
                    echo '<div class="selected-box">
                            <span> Chọn tất cả <input type="checkbox" onClick="toggle(this)" /></span>
                                <button type="submit" name="delete_multiple_student"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </div>';
                }
            ?>
            <?php 
                $sql_trang = "SELECT COUNT(*) AS total FROM sinhvien ";
                $result_trang = $conn->query($sql_trang);
                require "pagination.php"; 
            ?>  
        </div> 
    </div>       
        
    <?php include('footer.php'); ?>  
    
</body>
</html>