<?php 
include('config.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ma_lich_thi_list = $_POST['ma_lich_thi'];
    $msv = $_POST['msv'];

    foreach ($ma_lich_thi_list as $ma_lich_thi) {
        // Kiểm tra đã đăng ký chưa
        $stmt_check = $conn->prepare("SELECT * FROM dangkythi WHERE ma_lich_thi = ? AND msv = ?");
        $stmt_check->bind_param("ss", $ma_lich_thi, $msv);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            echo "Lịch thi $ma_lich_thi đã tồn tại!";
            $stmt_check->close();
            continue;
        }
        $stmt_check->close();

        // Lấy mã học phần từ lịch thi
        $stmt_get_hp = $conn->prepare("SELECT ma_hoc_phan FROM lichthi WHERE ma_lich_thi = ?");
        $stmt_get_hp->bind_param("s", $ma_lich_thi);
        $stmt_get_hp->execute();
        $result_hp = $stmt_get_hp->get_result();

        if ($result_hp->num_rows > 0) {
            $row = $result_hp->fetch_assoc();
            $ma_hoc_phan = $row['ma_hoc_phan'];
            $stmt_get_hp->close();

            // Kiểm tra xung đột mã học phần
            $stmt_conflict = $conn->prepare("
                SELECT dangkythi.ma_lich_thi 
                FROM dangkythi 
                JOIN lichthi ON dangkythi.ma_lich_thi = lichthi.ma_lich_thi 
                WHERE dangkythi.msv = ? AND lichthi.ma_hoc_phan = ?
            ");
            $stmt_conflict->bind_param("ss", $msv, $ma_hoc_phan);
            $stmt_conflict->execute();
            $result_conflict = $stmt_conflict->get_result();

            if ($result_conflict->num_rows > 0) {
                $row_conflict = $result_conflict->fetch_assoc();
                $ma_lich_thi_old = $row_conflict['ma_lich_thi'];
                $stmt_conflict->close();

                // Cập nhật lịch thi mới
                $stmt_update = $conn->prepare("UPDATE dangkythi SET ma_lich_thi = ? WHERE ma_lich_thi = ? AND msv = ?");
                $stmt_update->bind_param("sss", $ma_lich_thi, $ma_lich_thi_old, $msv);
                if (!$stmt_update->execute()) {
                    echo "Lỗi cập nhật: " . $stmt_update->error;
                }
                $stmt_update->close();
            } else {
                $stmt_conflict->close();

                // Chèn lịch thi mới nếu không có xung đột
                $stmt_insert = $conn->prepare("INSERT INTO dangkythi (ma_lich_thi, msv) VALUES (?, ?)");
                $stmt_insert->bind_param("ss", $ma_lich_thi, $msv);
                if (!$stmt_insert->execute()) {
                    echo "Lỗi: " . $stmt_insert->error;
                }
                $stmt_insert->close();
            }
        } else {
            $stmt_get_hp->close();
        }
    }

    header("Location: home_student.php");
    exit();
}
?>
