<?php include('header.php'); 
$stmt = $conn->prepare("SELECT ngay_bat_dau, ngay_ket_thuc FROM cauhinh_dangky ORDER BY id DESC LIMIT 1");
$stmt->execute();
$result = $stmt->get_result();
$is_locked = true;

if ($row = $result->fetch_assoc()) {
    $today = date("Y-m-d");
    $start = $row['ngay_bat_dau'];
    $end = $row['ngay_ket_thuc'];

    if ($today >= $start && $today <= $end) {
        $is_locked = false;
    }
}
$stmt->close();
?> 
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang đăng kí lịch thi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="styles.css">
</head>
<body onload="myChoice()"> 

    <div class="container">
        <div class="register">
            <h2>Đăng kí lịch thi</h2>
            <select name="ma_hoc_phan" class="course-filter" onchange="filterFunction()">
                <option value="">Chọn học phần</option>
                <?php
                    $stmt = $conn->prepare("SELECT * FROM hocphan");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['ma_hoc_phan'] . '">' . $row['ten_hoc_phan'] . '</option>';
                    }
                    $stmt->close();
                ?>
            </select>

            <h3>Danh sách lịch thi:</h3>
            <form action="course_registration.php" method="post">
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
                            $sql = "SELECT * FROM lichthi 
                                    INNER JOIN hocphan ON lichthi.ma_hoc_phan = hocphan.ma_hoc_phan 
                                    ORDER BY lichthi.ma_hoc_phan ASC";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) {
                                if (empty($row['ma_phong'])) continue;

                                $ma_lich_thi = $row["ma_lich_thi"];
                                $ma_hoc_phan = $row["ma_hoc_phan"];
                                $ngay_thi_parts = explode("-", $row["ngay_thi"]);
                                $ngay_thi_update = $ngay_thi_parts[2] . "-" . $ngay_thi_parts[1] . "-" . $ngay_thi_parts[0];

                                echo '<tr>';
                                $disabled_attr = $is_locked ? 'disabled' : '';
                                echo '<td><input type="checkbox" class="choice" name="ma_lich_thi[]" value="'. $ma_lich_thi .'" style="cursor: pointer;" ' . $disabled_attr . '></td>';
                                echo '<td>' . $ma_hoc_phan . '</td>';
                                echo '<td>' . $row["ten_hoc_phan"] . '</td>';

                                // Lấy sức chứa
                                $stmt_phong = $conn->prepare("SELECT suc_chua FROM phongthi WHERE ma_phong = ?");
                                $stmt_phong->bind_param("s", $row["ma_phong"]);
                                $stmt_phong->execute();
                                $result_phong = $stmt_phong->get_result();
                                $suc_chua = ($row_phong = $result_phong->fetch_assoc()) ? $row_phong["suc_chua"] : 0;
                                $stmt_phong->close();
                                echo '<td>' . $suc_chua . '</td>';

                                // Đếm sinh viên đã đăng ký
                                $stmt_count = $conn->prepare("SELECT COUNT(*) AS so_luong FROM dangkythi WHERE ma_lich_thi = ?");
                                $stmt_count->bind_param("s", $ma_lich_thi);
                                $stmt_count->execute();
                                $result_count = $stmt_count->get_result();
                                $so_luong = ($row_count = $result_count->fetch_assoc()) ? $row_count['so_luong'] : 0;
                                $stmt_count->close();

                                $con_lai = max(0, $suc_chua - $so_luong);
                                echo '<td class="remain" style="color: red;">' . $con_lai . '</td>';
                                echo '<td>' . $ngay_thi_update . ' từ ' . $row["gio_bat_dau"] . ' - ' . $row["gio_ket_thuc"] . ', Ph ' . $row["ma_phong"] . '</td>';
                                echo '</tr>';
                            }

                            $stmt->close();
                        ?>
                    </tbody>
                </table>
                <button type="submit">Đăng kí</button>
            </form>
        </div>

        <div class="schedule">
            <h3>Danh sách lịch thi đã đăng ký: <span style="color: red;">
                <?php
                    $stmt = $conn->prepare("SELECT COUNT(*) AS so_luong FROM dangkythi WHERE msv = ?");
                    $stmt->bind_param("s", $msv);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    echo $row['so_luong'];
                    $stmt->close();
                ?>
            môn</span></h3>

            <table class="schedule-table">
                <thead>
                    <tr>
                        <th>Mã MH</th>
                        <th>Tên môn học</th>
                        <th>Lịch thi</th>
                        <th>Ngày đăng ký</th>
                        <th>Xóa đăng ký</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $stmt = $conn->prepare("SELECT dangkythi.ma_lich_thi, dangkythi.msv, dangkythi.ngay_dang_ky, 
                            lichthi.ngay_thi, lichthi.gio_bat_dau, lichthi.gio_ket_thuc, lichthi.ma_phong, 
                            hocphan.ten_hoc_phan, hocphan.ma_hoc_phan
                            FROM dangkythi
                            INNER JOIN lichthi ON dangkythi.ma_lich_thi = lichthi.ma_lich_thi
                            INNER JOIN hocphan ON lichthi.ma_hoc_phan = hocphan.ma_hoc_phan
                            WHERE dangkythi.msv = ?");
                        $stmt->bind_param("s", $msv);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($row = $result->fetch_assoc()) {
                            $ngay_thi_parts = explode("-", $row["ngay_thi"]);
                            $ngay_thi_update = $ngay_thi_parts[2] . "-" . $ngay_thi_parts[1] . "-" . $ngay_thi_parts[0];

                            echo '<tr>';
                            echo '<td>' . $row['ma_hoc_phan'] . '</td>';
                            echo '<td>' . $row["ten_hoc_phan"] . '</td>';
                            echo '<td data-value="'. $row['ma_lich_thi'] .'">' . $ngay_thi_update . ' từ ' . $row["gio_bat_dau"] . ' - ' . $row["gio_ket_thuc"] . ', Ph '. $row["ma_phong"] .'</td>';
                            echo '<td>' . $row["ngay_dang_ky"] . '</td>';
                            echo '<td><a href="delete.php?msv=' . urlencode($msv) . '&ma_lich_thi=' . urlencode($row['ma_lich_thi']) . '"><i id="delete-icon" class="fa-solid fa-trash-can"></i></a></td>';
                            echo '</tr>';
                        }

                        $stmt->close();
                    ?>
                </tbody>
            </table>
            <form action="export.php?msv=<?php echo htmlspecialchars($msv); ?>" method="post">
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
        
        // Khai báo selectedCourses
        let selectedCourses = {};

        // Vô hiệu hóa checkbox nếu số lượng còn lại là 0
        checkboxes.forEach((checkbox, index) => {
            let remainValue = parseInt(remains[index].innerText.trim());
            if (remainValue === 0) {
                checkbox.disabled = true;
                remains[index].style.color = "black";
            }
        });

        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener("change", function() {
                let maHocPhan = checkbox.closest("tr").querySelector("td:nth-child(2)").textContent.trim(); // Lấy mã học phần của dòng
                if (checkbox.checked) {
                    // Nếu checkbox được chọn, lưu lại học phần đã chọn
                    if (selectedCourses[maHocPhan]) {
                        // Nếu đã có lịch thi nào được chọn cho học phần này, bỏ chọn checkbox đó
                        let oldCheckbox = document.querySelector(`input[type="checkbox"][value="${selectedCourses[maHocPhan]}"]`);
                        if (oldCheckbox) {
                            oldCheckbox.checked = false; // Bỏ chọn checkbox cũ
                        }
                    }
                    // Lưu lại lịch thi hiện tại
                    selectedCourses[maHocPhan] = checkbox.value;
                } else {
                    // Nếu bỏ chọn checkbox, xóa học phần khỏi mảng
                    delete selectedCourses[maHocPhan];
                }
            });
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

    // Lọc học phần
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
