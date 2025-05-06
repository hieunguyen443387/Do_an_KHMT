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
                <a id="add-button" href="add_course.php">Thêm học phần</a>
                <form action="import.php" method="post" enctype="multipart/form-data" class="upload-form">
                    <label for="file-upload" class="custom-file-upload">
                        Choose File
                    </label>
                    <button type="submit" id="import-button" name="import-course-button">Upload</button>
                    <input id="file-upload" type="file" name="excel_file" accept=".xls,.xlsx" required>
                </form>
                <div class="search">
                    <input type="text" id="search-input" placeholder="Nhập mã học phần hoặc tên học phần..." onkeyup="searchFunction()">
                    <button id="search-button" onclick="searchFunction()"><i class="fa-solid fa-magnifying-glass"></i></button>          
                </div>

               
            </div>
            <table class="crud-table">
                <thead>
                    <tr>
                        <th>Chọn</th>
                        <th>STT</th>
                        <th>Mã học phần</th>
                        <th>Tên học phần</th>
                        <th>Số tín chỉ</th>
                        <th>Sửa</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <form action="delete.php" method="post">

                        <?php         
                            require "limit_page.php";   

                            // Truy vấn danh sách học phần bằng prepared statement
                            $stmt = $conn->prepare("SELECT * FROM hocphan LIMIT ? OFFSET ?");
                            $stmt->bind_param("ii", $limit, $offset);  // "ii" = hai số nguyên
                            $stmt->execute();
                            $result_hoc_phan = $stmt->get_result();
                            
                            if ($result_hoc_phan->num_rows > 0) {
                                $stt = $offset + 1;
                                while($row = $result_hoc_phan->fetch_assoc()) {
                                    // Escape dữ liệu khi hiển thị
                                    $ma_hoc_phan = htmlspecialchars($row["ma_hoc_phan"]);
                                    $ten_hoc_phan = htmlspecialchars($row["ten_hoc_phan"]);
                                    $so_tin_chi = htmlspecialchars($row["so_tin_chi"]);

                                    echo '<tr>';
                                    echo '<td><input type="checkbox" name="select_all[]" value="' . $ma_hoc_phan . '"></td>';
                                    echo '<td>' . $stt++ . '</td>';
                                    echo '<td>' . $ma_hoc_phan . '</td>';
                                    echo '<td>' . $ten_hoc_phan . '</td>';
                                    echo '<td>' . $so_tin_chi . '</td>';
                                    echo '<td id="update-icon"><a href="update_course.php?ma_hoc_phan=' . urlencode($ma_hoc_phan) . '"><i class="fa-solid fa-pen-to-square"></i></a></td>';
                                    echo '<td id="delete-icon"><a href="delete.php?ma_hoc_phan=' . urlencode($ma_hoc_phan) . '" onclick="return confirmDelete(\'' . $ma_hoc_phan . '\')"><i class="fa-solid fa-trash-can"></i></a></td>';   
                                    echo '</tr>';  
                                }
                            } else {
                                echo '<tr>
                                <td colspan="10" style="text-align: center; padding: 20px; font-style: italic; color: #777;">
                                    <div style="display: inline-block; padding: 10px 20px; border-radius: 10px;">
                                        Chưa có học phần
                                    </div>
                                </td>
                            </tr>';
                            }                        
                            $stmt->close();
                        ?>

                </tbody>

            </table>
            <?php
                if ($result_hoc_phan->num_rows > 0) {
                    echo '<div class="selected-box">
                            <span> Chọn tất cả <input type="checkbox" onClick="toggle(this)" /></span>
                                <button type="submit" name="delete_multiple_course" onclick="return confirmDeleteAll()"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </div>';
                }
            ?>
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