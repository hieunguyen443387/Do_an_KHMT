<?php
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    
    require '../vendor/autoload.php';
    require 'config.php'; 

    if (isset($_POST['export-schedule-button']) && isset($_GET['msv'])) {
        $msv = $_GET['msv'];

        $sql_dang_ki_thi = "SELECT dangkythi.msv, dangkythi.ngay_dang_ky, 
               lichthi.ngay_thi, lichthi.gio_bat_dau, lichthi.gio_ket_thuc, lichthi.ma_phong, 
               hocphan.ten_hoc_phan, hocphan.ma_hoc_phan
                FROM dangkythi
                INNER JOIN lichthi ON dangkythi.ma_lich_thi = lichthi.ma_lich_thi
                INNER JOIN hocphan ON lichthi.ma_hoc_phan = hocphan.ma_hoc_phan
                WHERE dangkythi.msv = '$msv'";
                
        $result_dang_ki_thi = $conn->query($sql_dang_ki_thi);

        if (!$result_dang_ki_thi) {
            die("Lỗi truy vấn SQL: " . $conn->error);
        }

        if ($result_dang_ki_thi->num_rows > 0) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Đặt tiêu đề cột
            $sheet->setCellValue('A1', 'STT');
            $sheet->setCellValue('B1', 'Mã môn học');
            $sheet->setCellValue('C1', 'Tên môn học');
            $sheet->setCellValue('D1', 'Ngày đăng ký');

            // Đổ dữ liệu
            $rowCount = 2;
            $stt = 1;
            while ($data = $result_dang_ki_thi->fetch_assoc()) {
                $sheet->setCellValue('A' . $rowCount, $stt++);
                $sheet->setCellValue('B' . $rowCount, $data['ma_hoc_phan']);
                $sheet->setCellValue('C' . $rowCount, $data['ten_hoc_phan']);
                $sheet->setCellValue('D' . $rowCount, $data['ngay_dang_ky']); // Đã sửa lỗi
                $rowCount++;
            }

            // Xuất file Excel
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="bang_dang_ki.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');

            exit();
        } else {
            echo "Không có dữ liệu để xuất!";
            exit();
        }
    }
?>
