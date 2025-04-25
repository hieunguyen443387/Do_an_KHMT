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
            <h3>Danh sách phòng thi:</h3>
            <div class="button-group">
                <a id="add-button" href="add_class.php">Thêm phòng thi</a>
                <form action="import.php" method="post" enctype="multipart/form-data" class="upload-form">
                    <label for="file-upload" class="custom-file-upload">
                        Choose File
                    </label>
                    <button type="submit" id="import-button" name="import-class-button">Upload</button>
                    <input id="file-upload" type="file" name="excel_file" accept=".xls,.xlsx" required>
                </form>
                <div class="search">
                    <input type="text" id="search-input" placeholder="Nhập tên lớp..." onkeyup="searchFunction()">
                    <button id="search-button" onclick="searchFunction()"><i class="fa-solid fa-magnifying-glass"></i></button>          
                </div>

               
            </div>
            <table class="crud-table">
                <thead>
                    <tr>
                        <th>Chọn</th>
                        <th>STT</th>
                        <th>Mã phòng</th>
                        <th>Sức chứa</th>
                        <th>Sửa</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <form action="delete.php" method="post">

                    <?php         
                        require "limit_page.php";   

                        // Chuyển sang prepared statement
                        $stmt = $conn->prepare("SELECT * FROM phongthi LIMIT ? OFFSET ?");
                        $stmt->bind_param("ii", $limit, $offset);  // "ii" là 2 số nguyên
                        $stmt->execute();
                        $result_phong_thi = $stmt->get_result();

                        if ($result_phong_thi->num_rows > 0) {
                            $stt = $offset + 1;
                            while($row = $result_phong_thi->fetch_assoc()) {
                                $ma_phong = htmlspecialchars($row["ma_phong"]);
                                $suc_chua = htmlspecialchars($row["suc_chua"]);

                                echo '<tr>';
                                echo '<td><input type="checkbox" name="select_all[]" value="' . $ma_phong . '"></td>';
                                echo '<td>' . $stt++ . '</td>';
                                echo '<td>' . $ma_phong . '</td>';
                                echo '<td>' . $suc_chua . '</td>';
                                echo '<td id="update-icon"><a href="update_class.php?ma_phong=' . urlencode($ma_phong) . '"><i class="fa-solid fa-pen-to-square"></i></a></td>';
                                echo '<td id="delete-icon"><a href="delete.php?ma_phong=' . urlencode($ma_phong) . '"><i class="fa-solid fa-trash-can"></i></a></td>';   
                                echo '</tr>';  
                            }
                        } else {
                            echo "Chưa có phòng thi";
                        }     
                        $stmt->close();                   
                    ?>

                </tbody>

            </table>
            <?php
                if ($result_phong_thi->num_rows > 0) {
                    echo '<div class="selected-box">
                            <span> Chọn tất cả <input type="checkbox" onClick="toggle(this)" /></span>
                                <button type="submit" name="delete_multiple_class"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </div>';
                }
            ?>
            <?php 
                $sql_trang = "SELECT COUNT(*) AS total FROM phongthi ";
                $result_trang = $conn->query($sql_trang);
                require "pagination.php"; 
            ?>  
        </div> 
    </div>       
        
    <?php include('footer.php'); ?>  
    
</body>
</html>