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
            <h3>Danh sách khoa:</h3>
            <div class="button-group">
                <a id="add-button" href="add_faculty.php">Thêm khoa</a>
                <form action="import.php" method="post" enctype="multipart/form-data" class="upload-form">
                    <label for="file-upload" class="custom-file-upload">
                        Choose File
                    </label>
                    <button type="submit" id="import-button" name="import-faculty-button">Upload</button>
                    <input id="file-upload" type="file" name="excel_file" accept=".xls,.xlsx" required>
                </form>
                <form action="export.php" method="post">
                    <button type="" id="export-button" name="export-faculty-button"><i class="fa-solid fa-file-export"></i>Export</button>
                </form>
                <div class="search">
                    <input type="text" id="search-input" placeholder="Nhập mã khoa hoặc tên khoa...">
                    <button id="search-button"><i class="fa-solid fa-magnifying-glass"></i></button>          
                </div>

               
            </div>
            <table class="crud-table">
                <thead>
                    <tr>
                        <th>Chọn</th>
                        <th>STT</th>
                        <th>Mã khoa</th>
                        <th>Tên khoa</th>
                        <th>Sửa</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <form action="delete.php" method="post">

                    <?php         
                        require "limit_page.php";   
                        
                        $sql_khoa = "SELECT * FROM khoa LIMIT $limit OFFSET $offset";
                        $result_khoa = $conn->query($sql_khoa);
                        
                        if ($result_khoa->num_rows > 0) {
                            $stt = $offset + 1;
                            while($row = $result_khoa->fetch_assoc()) {
                                $ma_khoa = $row["ma_khoa"];

                                echo '<tr>';
                                echo '<td><input type="checkbox" name="select_all[]" value="' . $ma_khoa . '"></td>';
                                echo '<td>' . $stt++ . '</td>';
                                echo '<td>' . $ma_khoa . '</td>';
                                echo '<td>' . $row["ten_khoa"] . '</td>';
                                echo '<td id="update-icon"><a href="update_faculty.php?ma_khoa=' . $ma_khoa . '"><i class="fa-solid fa-pen-to-square"></i></a></td>';
                                echo '<td id="delete-icon"><a href="delete.php?ma_khoa=' . $ma_khoa . '"><i class="fa-solid fa-trash-can"></i></a></td>';   
                                echo '</tr>';  
                            }
                        } else {
                            echo "Chưa có khoa";
                        }                        
                    ?>

                </tbody>

            </table>
            <?php
                if ($result_khoa->num_rows > 0) {
                    echo '<div class="selected-box">
                            <span> Chọn tất cả <input type="checkbox" onClick="toggle(this)" /></span>
                                <button type="submit" name="delete_multiple_student"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </div>';
                }
            ?>
            <?php 
                $sql_trang = "SELECT COUNT(*) AS total FROM khoa ";
                $result_trang = $conn->query($sql_trang);
                require "pagination.php"; 
            ?>  
        </div> 
    </div>       
        
    <?php include('footer.php'); ?>  
    
</body>
</html>