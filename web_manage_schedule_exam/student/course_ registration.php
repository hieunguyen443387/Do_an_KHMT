<?php 
include('config.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ma_lich_thi_list = $_POST['ma_lich_thi']; // Lấy tất cả mã lịch thi được chọn
    $msv = $_POST['msv'];

    foreach ($ma_lich_thi_list as $ma_lich_thi) {
        // Kiểm tra xem sinh viên đã đăng ký lịch thi này chưa
        $sql_dang_ki_thi = "SELECT * FROM dangkythi WHERE ma_lich_thi = '$ma_lich_thi' AND msv = '$msv'";
        $result_dang_ki_thi = $conn->query($sql_dang_ki_thi);

        if ($result_dang_ki_thi->num_rows > 0) {
            echo "Lịch thi $ma_lich_thi đã tồn tại!";
            continue;
        }

        // Lấy mã học phần của lịch thi mà sinh viên đang đăng ký
        $sql_get_ma_hp = "SELECT ma_hoc_phan FROM lichthi WHERE ma_lich_thi = '$ma_lich_thi'";
        $result_ma_hp = $conn->query($sql_get_ma_hp);
        if ($result_ma_hp->num_rows > 0) {
            $row = $result_ma_hp->fetch_assoc();
            $ma_hoc_phan = $row['ma_hoc_phan'];

            // Kiểm tra xem sinh viên đã đăng ký lịch thi nào khác có cùng mã học phần chưa
            $sql_check_conflict = "SELECT * FROM dangkythi 
                JOIN lichthi ON dangkythi.ma_lich_thi = lichthi.ma_lich_thi 
                WHERE dangkythi.msv = '$msv' AND lichthi.ma_hoc_phan = '$ma_hoc_phan'";

            $result_check_conflict = $conn->query($sql_check_conflict);
            if ($result_check_conflict->num_rows > 0) {
                continue;
            }

            // Nếu không có xung đột, tiến hành đăng ký lịch thi
            $sql_insert = "INSERT INTO dangkythi (ma_lich_thi, msv) VALUES ('$ma_lich_thi', '$msv')";
            if ($conn->query($sql_insert) === FALSE) {
                echo "Lỗi: " . $sql_insert . "<br>" . $conn->error;
            }
        }
    }

    header("Location: home_student.php");
    exit();
}
?>
