<?php
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    require '../vendor/autoload.php';
    require 'config.php'; 

    if (isset($_POST['export-schedule-button']) && isset($_GET['msv'])) {
        $msv = $_GET['msv'];

        // Sử dụng Prepared Statement để chống SQL Injection
        $sql_dang_ki_thi = "SELECT dangkythi.msv, dangkythi.ngay_dang_ky, 
               lichthi.ngay_thi, lichthi.gio_bat_dau, lichthi.gio_ket_thuc, lichthi.ma_phong, 
               hocphan.ten_hoc_phan, hocphan.ma_hoc_phan
                FROM dangkythi
                INNER JOIN lichthi ON dangkythi.ma_lich_thi = lichthi.ma_lich_thi
                INNER JOIN hocphan ON lichthi.ma_hoc_phan = hocphan.ma_hoc_phan
                WHERE dangkythi.msv = ?";

        if ($stmt = $conn->prepare($sql_dang_ki_thi)) {
            $stmt->bind_param("s", $msv);
            $stmt->execute();
            $result_dang_ki_thi = $stmt->get_result();

            if ($result_dang_ki_thi->num_rows > 0) {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Đặt tiêu đề cột
                $sheet->setCellValue('A1', 'STT');
                $sheet->setCellValue('B1', 'Mã môn học');
                $sheet->setCellValue('C1', 'Tên môn học');
                $sheet->setCellValue('D1', 'Lịch thi');
                $sheet->setCellValue('E1', 'Ngày đăng ký');

                // Đổ dữ liệu
                $rowCount = 2;
                $stt = 1;
                while ($data = $result_dang_ki_thi->fetch_assoc()) {
                    if (empty($data['ma_phong'])) continue;

                    // Chuyển định dạng ngày thi từ yyyy-mm-dd thành dd-mm-yyyy
                    $dateParts = explode("-", $data["ngay_thi"]);
                    $ngay_thi_update = $dateParts[2] . "-" . $dateParts[1] . "-" . $dateParts[0];

                    $sheet->setCellValue('A' . $rowCount, $stt++);
                    $sheet->setCellValue('B' . $rowCount, $data['ma_hoc_phan']);
                    $sheet->setCellValue('C' . $rowCount, $data['ten_hoc_phan']);
                    $sheet->setCellValue('D' . $rowCount, "ngày $ngay_thi_update từ {$data['gio_bat_dau']} - {$data['gio_ket_thuc']}, Ph {$data['ma_phong']}");
                    $sheet->setCellValue('E' . $rowCount, $data['ngay_dang_ky']);
                    $rowCount++;
                }

                // Xuất file Excel
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment; filename="bang_dang_ki.xlsx"');
                header('Cache-Control: max-age=0');

                $writer = new Xlsx($spreadsheet);
                $writer->save('php://output');
                
                $stmt->close();
                exit();
            } else {
                echo "Không có dữ liệu để xuất!";
                
                $stmt->close();
                exit();
            }
        } else {
            die("Lỗi khi chuẩn bị truy vấn: " . $conn->error);
        }
    }
?>
