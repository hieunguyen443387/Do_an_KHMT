<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
require '../vendor/autoload.php';
require 'config.php'; 

if (isset($_POST['import-student-button']) && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file']['tmp_name'];
    
    if (!$file) {
        die("Vui lòng chọn file Excel!");
    }

    $spreadsheet = IOFactory::load($file);
    $worksheet = $spreadsheet->getActiveSheet();
    $data = $worksheet->toArray();

    // Bỏ qua hàng đầu tiên nếu là tiêu đề
    array_shift($data);

    foreach ($data as $row) {
        $i = 0;

        // Tìm cột đầu tiên có dữ liệu
        while ($i < count($row) && empty(trim($row[$i]))) {
            $i++;
        }

        // Đảm bảo có đủ cột dữ liệu để nhập
        if (!isset($row[$i+6])) {
            continue; // Bỏ qua dòng nếu thiếu dữ liệu
        }

        // Gán giá trị từ vị trí hợp lệ
        $msv = trim($row[$i]);
        $ho_dem = trim($row[$i+1]);
        $ten = trim($row[$i+2]);
        $ngay_sinh = trim($row[$i+3]);
        $lop = trim($row[$i+4]);
        $khoa = trim($row[$i+5]);
        $gioi_tinh = trim($row[$i+6]);

        // Nếu mã sinh viên rỗng, có phải kiểu số nguyên không bỏ qua hàng này
        if (empty($msv) || !ctype_digit($msv)) { 
            continue;
        }        
        
        // Kiểm tra xem mã sinh viên đã tồn tại chưa
        $sql_check = "SELECT * FROM sinhvien WHERE msv = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $msv);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            continue; // Nếu MSV đã tồn tại, bỏ qua
        }

        // Thêm vào database
        $sql_sinh_vien = "INSERT INTO sinhvien (msv, ho_dem, ten, ngay_sinh, lop, khoa, gioi_tinh, mat_khau) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_sinh_vien);

        if (DateTime::createFromFormat("Y-m-d", $ngay_sinh)) {
            $dateObject = DateTime::createFromFormat("Y-m-d", $ngay_sinh);
            $ngay_sinh_sql = $dateObject->format("Y-m-d");
            $part_sql = (explode("-",$ngay_sinh_sql));
            $mat_khau_sql = $part_sql[2] . $part_sql[1] . $part_sql[0];

            $stmt->bind_param("ssssssss", $msv, $ho_dem, $ten, $ngay_sinh_sql, $lop, $khoa, $gioi_tinh, $mat_khau_sql);
            $stmt->execute();
        } else{
            $part = (explode("-",$ngay_sinh));
            $mat_khau = $part[2] . $part[1] . $part[0];
            $stmt->bind_param("ssssssss", $msv, $ho_dem, $ten, $ngay_sinh, $lop, $khoa, $gioi_tinh, $mat_khau);
            $stmt->execute();
        }
        
    }
    
    header('Location: manage_student.php');
    exit(0);
}

if (isset($_POST['import-teacher-button']) && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file']['tmp_name'];
    
    if (!$file) {
        die("Vui lòng chọn file Excel!");
    }

    $spreadsheet = IOFactory::load($file);
    $worksheet = $spreadsheet->getActiveSheet();
    $data = $worksheet->toArray();

    // Bỏ qua hàng đầu tiên nếu là tiêu đề
    array_shift($data);

    foreach ($data as $row) {
        $i = 0;

        // Tìm cột đầu tiên có dữ liệu
        while ($i < count($row) && empty(trim($row[$i]))) {
            $i++;
        }

        // Đảm bảo có đủ cột dữ liệu để nhập
        if (!isset($row[$i+5])) {
            continue; // Bỏ qua dòng nếu thiếu dữ liệu
        }

        // Gán giá trị từ vị trí hợp lệ
        $mgv = trim($row[$i]);
        $ho_dem = trim($row[$i+1]);
        $ten = trim($row[$i+2]);
        $ngay_sinh = trim($row[$i+3]);
        $khoa = trim($row[$i+4]);
        $gioi_tinh = trim($row[$i+5]);

        // Nếu mã sinh viên rỗng, có phải kiểu số nguyên không, nếu không bỏ qua hàng này bỏ qua hàng này
        if (empty($mgv) || !ctype_digit($mgv)) { 
            continue;
        }        
        
        // Kiểm tra xem mã sinh viên đã tồn tại chưa
        $sql_check = "SELECT * FROM giangvien WHERE mgv = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $mgv);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            continue; // Nếu MGV đã tồn tại, bỏ qua
        }

        // Thêm vào database
        $sql_giang_vien = "INSERT INTO giangvien (mgv, ho_dem, ten, ngay_sinh, khoa, gioi_tinh) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_giang_vien);
        $stmt->bind_param("ssssss", $mgv, $ho_dem, $ten, $ngay_sinh, $khoa, $gioi_tinh);
        $stmt->execute();
        
    }
    

    header('Location: manage_teacher.php');
    exit(0);
}

?>
