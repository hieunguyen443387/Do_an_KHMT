<?php
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    require '../vendor/autoload.php';
    require 'config.php'; 

    if(isset($_POST['export-student-button']) && isset($_GET['ma_lich_thi'])) {
        $ma_lich_thi = $_GET['ma_lich_thi'];

        $sql_sinh_vien = "SELECT * FROM dangkythi 
                JOIN sinhvien ON dangkythi.msv = sinhvien.msv 
                WHERE dangkythi.ma_lich_thi = ?";
        
        if ($stmt = $conn->prepare($sql_sinh_vien)) {
            $stmt->bind_param("s", $ma_lich_thi); 
            
            $stmt->execute();
            $result_sinh_vien = $stmt->get_result();

            if ($result_sinh_vien->num_rows > 0) {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Đặt tiêu đề cột
                $sheet->setCellValue('A1', 'STT');
                $sheet->setCellValue('B1', 'Mã sinh viên');
                $sheet->setCellValue('C1', 'Họ đệm');
                $sheet->setCellValue('D1', 'Tên');
                $sheet->setCellValue('E1', 'Ngày sinh');
                $sheet->setCellValue('F1', 'Lớp');
                $sheet->setCellValue('G1', 'Khoa');
                $sheet->setCellValue('H1', 'Giới tính');

                // Đổ dữ liệu
                $rowCount = 2;
                $stt = 1;
                while ($data = $result_sinh_vien->fetch_assoc()) {
                    // Lấy danh sách sinh viên
                    $sheet->setCellValue('A' . $rowCount, $stt++);
                    $sheet->setCellValue('B' . $rowCount, $data['msv']);
                    $sheet->setCellValue('C' . $rowCount, $data['ho_dem']);
                    $sheet->setCellValue('D' . $rowCount, $data['ten']);
                    $sheet->setCellValue('E' . $rowCount, $data['lop']); 
                    $sheet->setCellValue('F' . $rowCount, $data['khoa']);
                    $sheet->setCellValue('G' . $rowCount, $data['ngay_sinh']);
                    $sheet->setCellValue('H' . $rowCount, $data['gioi_tinh']);
                    $rowCount++;
                }

                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="bang_dang_ki.xlsx"');
                header('Cache-Control: max-age=0');

                $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
                $writer->save('php://output');

                exit();
            } else {
                echo "Không có dữ liệu để xuất!";
            }
            $stmt->close();
        } else {
            echo "Lỗi trong việc chuẩn bị câu truy vấn!";
        }
    }
?>
