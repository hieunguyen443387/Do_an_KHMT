<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles.css">
    <title>Trang đăng kí lịch thi</title>
    
</head>
<body onload = "myChoice()"> 

    <?php include('header.php'); ?> 

    <div class="container">

        <div class="register">
            <h2>Đăng kí lịch thi</h2>
            <select name="ma_hoc_phan" class="course-filter" onchange="filterFunction()">
                <option value="">Chọn học phần</option>
                <?php
                    $sql_hoc_phan = "SELECT * FROM hocphan";
                    $result_hoc_phan = $conn->query($sql_hoc_phan);

                    if ($result_hoc_phan->num_rows > 0) {
                        while($row = $result_hoc_phan->fetch_assoc()) {
                            echo '<option value="' . $row['ma_hoc_phan'] . '" id="ma_hoc_phan">' . $row['ten_hoc_phan'] . '</option>';
                        }
                    }
                ?>
            </select>

            <h3>Danh sách lịch thi:</h3>

            <form action="course_ registration.php" method="post">
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th>Chọn</th>
                            <th>Mã MH</th>
                            <th>Tên môn học</th>
                            <th>Số lượng</th>
                            <th>Còn lại</th>
                            <th>Lịch thi</th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php         
                        
                            echo '<input type="hidden" id="msv" name="msv" value="'. $msv . '">';

                            $sql_lich_thi = "SELECT * FROM lichthi 
                            INNER JOIN hocphan ON lichthi.ma_hoc_phan = hocphan.ma_hoc_phan ";
                            $result_lich_thi = $conn->query($sql_lich_thi);
                            
                            if ($result_lich_thi->num_rows > 0) {
                                while($row = $result_lich_thi->fetch_assoc()) {
                                    if (empty($row['ma_phong'])) {
                                        continue;
                                    }
                                    $ma_phong = $row["ma_phong"];
                                    $ma_lich_thi = $row["ma_lich_thi"];
                                    $ma_hoc_phan = $row["ma_hoc_phan"];
                                    $ngay_thi = $row["ngay_thi"];
                                    $part = (explode("-",$ngay_thi));
                                    $ngay_thi_update = $part[2] . "-" . $part[1]. "-" . $part[0];
                                    $gio_ket_thuc = $row["gio_ket_thuc"];
                                    $gio_bat_dau = $row["gio_bat_dau"];
                                    echo '<tr>';
                                    echo '<td><input type="checkbox" class="choice" name="ma_lich_thi[]" value="'. $ma_lich_thi .'" style="cursor: pointer;"></td>';
                                    echo '<td>' . $ma_hoc_phan . '</td>';
                                    echo '<td>' . $row["ten_hoc_phan"] . '</td>';

                                    $sql_phong_thi = "SELECT * FROM phongthi where ma_phong = '$ma_phong'";
                                    $result_phong_thi = $conn->query($sql_phong_thi);
                                    
                                    if ($result_phong_thi->num_rows > 0) {
                                        $row = $result_phong_thi->fetch_assoc();
                                        $suc_chua = $row["suc_chua"];
                                        echo '<td>' . $suc_chua . '</td>';
                                    } else{
                                        $suc_chua = 0;
                                        echo '<td>' . $suc_chua . '</td>';
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

                                    //Tính số lượng còn lại
                                    $con_lai = $suc_chua - $so_luong;
                                    if ($con_lai >= 0){
                                        $con_lai_moi = $con_lai;
                                    } else{
                                        $con_lai_moi = 0;
                                    }
                                    
                                    echo '<td class="remain" style="color: red;">' . $con_lai_moi . '</td>';

                                    echo '<td>' . $ngay_thi_update . ' từ ' . $gio_bat_dau . ' - ' . $gio_ket_thuc . ', Ph '. $ma_phong .'</td>';
                                    echo '</tr>';  

                                }
                            } else {
                                echo "Chưa có lịch thi";
                            }                        
                        ?>         
                    </tbody>
                </table>

                <button type="submit">Đăng kí</button>
            </form>
        </div>
        

        <div class="schedule">
            <h3>Danh sách lịch thi đã đăng ký: <span style="color: red;">
                <?php //Truy vấn số môn đã đăng kí
                    $sql = "SELECT COUNT(*) AS so_luong FROM dangkythi WHERE msv = '$msv'";
                    $result = $conn->query($sql);
                
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo $row['so_luong'] ;
                        
                    } else {
                        $so_luong = 0; 
                    } 
                    ?> 
            môn</span></h3>
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th>Mã MH</th>
                        <th>Tên môn học</th>
                        <th>Lịch thi</th>
                        <th>Ngày đăng ký</th>
                    </tr>
                </thead>
                <tbody>
                    <?php         

                        $sql_dang_ki_thi = "SELECT dangkythi.ma_lich_thi, dangkythi.msv, dangkythi.ngay_dang_ky, 
                        lichthi.ngay_thi, lichthi.gio_bat_dau, lichthi.gio_ket_thuc, lichthi.ma_phong, 
                        hocphan.ten_hoc_phan, hocphan.ma_hoc_phan
                        FROM dangkythi
                        INNER JOIN lichthi ON dangkythi.ma_lich_thi = lichthi.ma_lich_thi
                        INNER JOIN hocphan ON lichthi.ma_hoc_phan = hocphan.ma_hoc_phan
                        WHERE dangkythi.msv = '$msv'";
                        $result_dang_ki_thi = $conn->query($sql_dang_ki_thi);
                        
                        if ($result_dang_ki_thi->num_rows > 0) {
                            while($row = $result_dang_ki_thi->fetch_assoc()) {
                                if (empty($row['ma_phong'])) {
                                    continue;
                                }
                                $ma_lich_thi = $row["ma_lich_thi"];
                                $ngay_dang_ky = $row["ngay_dang_ky"];
                                $ngay_thi = $row["ngay_thi"];
                                $part = (explode("-",$ngay_thi));
                                $ngay_thi_update = $part[2] . "-" . $part[1]. "-" . $part[0];
                                $gio_ket_thuc = $row["gio_ket_thuc"];
                                $gio_bat_dau = $row["gio_bat_dau"];
                                $ma_hoc_phan = $row['ma_hoc_phan'];
                                $ma_lich_thi = $row['ma_lich_thi'];
                                $ma_phong = $row['ma_phong'];
                
                                echo '<tr>';
                                echo '<td>' . $ma_hoc_phan . '</td>';
                                echo '<td>' . $row["ten_hoc_phan"] . '</td>';

                                //Hiển thị lịch thi
                                echo '<td data-value="'. $ma_lich_thi .'" >' . $ngay_thi_update . ' từ ' . $gio_bat_dau . ' - ' . $gio_ket_thuc . ', Ph '. $ma_phong .'</td>';
                                echo '<td>' . $ngay_dang_ky . '</td>';
                                echo '</tr>'; 
                                }                                
                            
                        } else {
                            echo "Chưa có lịch thi";
                        }                         
                    ?>    
                </tbody>
            </table>
            <form action="export.php?msv=<?php echo $msv; ?>" method="post">
                <button type="submit" name="export-schedule-button" class="export-schedule-button">
                    <i class="fa-solid fa-file-export"></i> Xuất phiếu đăng ký
                </button>
            </form>

        </div>   
    </div>     
        
    <?php include('footer.php'); ?> 

    <script>
        // Hiển thị dropdown đăng xuất
        function myFunction() {
            document.getElementById("myDropdown").classList.toggle("show");
        }
        
        window.onclick = function (e) {
            var user = document.querySelector(".user");
            var myDropdown = document.getElementById("myDropdown");
        
            if (!user.contains(e.target) && !myDropdown.contains(e.target)) {
                myDropdown.classList.remove("show");
            }
        };

        // Kiểm tra xem phòng đã đủ chưa
        function myChoice() {
            let checkboxes = document.querySelectorAll(".choice");
            let remains = document.querySelectorAll(".remain");
            let scheduleCells = document.querySelectorAll("td[data-value]");

            // Vô hiệu hóa checkbox nếu số lượng còn lại là 0
            checkboxes.forEach((checkbox, index) => {
                let remainValue = parseInt(remains[index].innerText.trim());
                if (remainValue === 0) {
                    checkbox.disabled = true;
                    remains[index].style.color = "black";
                }
            });

            // Tự động chọn checkbox nếu mã lịch thi trùng nhau và đổi màu nền
            checkboxes.forEach((checkbox) => {
                let checkboxValue = checkbox.value;

                scheduleCells.forEach((cell) => {
                    let cellValue = cell.getAttribute("data-value");
                    if (checkboxValue === cellValue) {
                        checkbox.disabled = true;
                        checkbox.checked = true;
                        checkbox.closest("tr").style.backgroundColor = "#dadada"; // Đổi màu nền thẻ tr
                    }
                });
            });
        }

        //Lọc học phần
        function filterFunction() {
            var select, filter, table, tr, td, i, txtValue;
            select = document.querySelector(".course-filter"); 
            filter = select.value.toUpperCase(); 
            table = document.querySelector(".schedule-table");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName("td")[1]; 
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (filter === "" || txtValue.toUpperCase() === filter) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

    </script>
</body>
</html>
