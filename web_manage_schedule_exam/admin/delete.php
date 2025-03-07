<?php
    include('config.php'); 

// Xóa sinh viên
    if (isset($_GET['msv'])) {
        $msv = $_GET['msv'];
    
        $sql_delete = "DELETE FROM sinhvien WHERE msv = '$msv'";
    
        if ($conn->query($sql_delete) === TRUE) {
            
            header("Location: manage_student.php");
            exit();
        } else {
            echo "Lỗi khi xóa sinh viên: " . $conn->error;
        }
    }

    if (isset($_POST['delete_multiple_student'])) {
        $all_msv = $_POST['select_all'];
        $extract_msv = implode(',', $all_msv);
        $sql_delete = "DELETE FROM sinhvien WHERE msv in($extract_msv) ";
        if ($conn->query($sql_delete) === TRUE) {
            
            header("Location: manage_student.php");
            exit();
        } else {
            echo "Lỗi khi xóa sinh viên: " . $conn->error;
        } 
    }

//Xóa giảng viên
    if (isset($_GET['mgv'])) {
        $mgv = $_GET['mgv'];
    
        $sql_delete = "DELETE FROM giangvien WHERE mgv = '$mgv'";
    
        if ($conn->query($sql_delete) === TRUE) {
            
            header("Location: manage_teacher.php");
            exit();
        } else {
            echo "Lỗi khi xóa giảng viên: " . $conn->error;
        }
    }

    if (isset($_POST['delete_multiple_teacher'])) {
        $all_mgv = $_POST['select_all'];
        $extract_mgv = implode(',', $all_mgv);
        $sql_delete = "DELETE FROM giangvien WHERE mgv in($extract_mgv) ";
        if ($conn->query($sql_delete) === TRUE) {
            
            header("Location: manage_teacher.php");
            exit();
        } else {
            echo "Lỗi khi xóa giảng viên: " . $conn->error;
        } 
    }

// Xóa phòng thi
    if (isset($_GET['ma_phong'])) {
        $ma_phong = $_GET['ma_phong'];
    
        $sql_delete = "DELETE FROM phongthi WHERE ma_phong = '$ma_phong'";
    
        if ($conn->query($sql_delete) === TRUE) {
            
            header("Location: manage_class.php");
            exit();
        } else {
            echo "Lỗi khi xóa phòng: " . $conn->error;
        }
    }

    if (isset($_POST['delete_multiple_class'])) {
        $all_ma_phong = $_POST['select_all'];
        $extract_ma_phong = "'" . implode("','", $all_ma_phong) . "'";
        $sql_delete = "DELETE FROM phongthi WHERE ma_phong in($extract_ma_phong) ";
        if ($conn->query($sql_delete) === TRUE) {
            
            header("Location: manage_class.php");
            exit();
        } else {
            echo "Lỗi khi xóa phòng: " . $conn->error;
        } 
    }

// Xóa học phần
    if (isset($_GET['ma_hoc_phan'])) {
        $ma_hoc_phan = $_GET['ma_hoc_phan'];
    
        $sql_delete = "DELETE FROM hocphan WHERE ma_hoc_phan = '$ma_hoc_phan'";
    
        if ($conn->query($sql_delete) === TRUE) {
            
            header("Location: manage_course.php");
            exit();
        } else {
            echo "Lỗi khi xóa học phần: " . $conn->error;
        }
    }

    if (isset($_POST['delete_multiple_course'])) {
        $all_ma_hoc_phan = $_POST['select_all'];
        $extract_ma_hoc_phan = implode(',', $all_ma_hoc_phan) ;
        $sql_delete = "DELETE FROM hocphan WHERE ma_hoc_phan in($extract_ma_hoc_phan) ";
        if ($conn->query($sql_delete) === TRUE) {
            
            header("Location: manage_course.php");
            exit();
        } else {
            echo "Lỗi khi xóa phòng: " . $conn->error;
        } 
    }
?>