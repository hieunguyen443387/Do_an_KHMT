<?php
    include('config.php'); 
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
?>