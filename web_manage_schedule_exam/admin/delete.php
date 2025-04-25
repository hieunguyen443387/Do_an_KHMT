<?php
include('config.php');

// Xóa sinh viên (theo msv GET)
if (isset($_GET['msv'])) {
    $stmt = $conn->prepare("DELETE FROM sinhvien WHERE msv = ?");
    $stmt->bind_param("s", $_GET['msv']);
    if ($stmt->execute()) {
        header("Location: manage_student.php");
        exit();
    } else {
        echo "Lỗi khi xóa sinh viên: " . $stmt->error;
    }
    $stmt->close();
}

// Xóa nhiều sinh viên (POST)
if (isset($_POST['delete_multiple_student'])) {
    $all_msv = $_POST['select_all'];
    $placeholders = implode(',', array_fill(0, count($all_msv), '?'));
    $stmt = $conn->prepare("DELETE FROM sinhvien WHERE msv IN ($placeholders)");
    $stmt->bind_param(str_repeat("s", count($all_msv)), ...$all_msv);
    if ($stmt->execute()) {
        header("Location: manage_student.php");
        exit();
    } else {
        echo "Lỗi khi xóa sinh viên: " . $stmt->error;
    }
    $stmt->close();
}

// Xóa giảng viên
if (isset($_GET['mgv'])) {
    $stmt = $conn->prepare("DELETE FROM giangvien WHERE mgv = ?");
    $stmt->bind_param("s", $_GET['mgv']);
    if ($stmt->execute()) {
        header("Location: manage_teacher.php");
        exit();
    } else {
        echo "Lỗi khi xóa giảng viên: " . $stmt->error;
    }
    $stmt->close();
}

if (isset($_POST['delete_multiple_teacher'])) {
    $all_mgv = $_POST['select_all'];
    $placeholders = implode(',', array_fill(0, count($all_mgv), '?'));
    $stmt = $conn->prepare("DELETE FROM giangvien WHERE mgv IN ($placeholders)");
    $stmt->bind_param(str_repeat("s", count($all_mgv)), ...$all_mgv);
    if ($stmt->execute()) {
        header("Location: manage_teacher.php");
        exit();
    } else {
        echo "Lỗi khi xóa giảng viên: " . $stmt->error;
    }
    $stmt->close();
}

// Xóa phòng thi
if (isset($_GET['ma_phong'])) {
    $stmt = $conn->prepare("DELETE FROM phongthi WHERE ma_phong = ?");
    $stmt->bind_param("s", $_GET['ma_phong']);
    if ($stmt->execute()) {
        header("Location: manage_class.php");
        exit();
    } else {
        echo "Lỗi khi xóa phòng: " . $stmt->error;
    }
    $stmt->close();
}

if (isset($_POST['delete_multiple_class'])) {
    $all_ma_phong = $_POST['select_all'];
    $placeholders = implode(',', array_fill(0, count($all_ma_phong), '?'));
    $stmt = $conn->prepare("DELETE FROM phongthi WHERE ma_phong IN ($placeholders)");
    $stmt->bind_param(str_repeat("s", count($all_ma_phong)), ...$all_ma_phong);
    if ($stmt->execute()) {
        header("Location: manage_class.php");
        exit();
    } else {
        echo "Lỗi khi xóa phòng: " . $stmt->error;
    }
    $stmt->close();
}

// Xóa học phần
if (isset($_GET['ma_hoc_phan'])) {
    $stmt = $conn->prepare("DELETE FROM hocphan WHERE ma_hoc_phan = ?");
    $stmt->bind_param("s", $_GET['ma_hoc_phan']);
    if ($stmt->execute()) {
        header("Location: manage_course.php");
        exit();
    } else {
        echo "Lỗi khi xóa học phần: " . $stmt->error;
    }
    $stmt->close();
}

if (isset($_POST['delete_multiple_course'])) {
    $all_ma_hoc_phan = $_POST['select_all'];
    $placeholders = implode(',', array_fill(0, count($all_ma_hoc_phan), '?'));
    $stmt = $conn->prepare("DELETE FROM hocphan WHERE ma_hoc_phan IN ($placeholders)");
    $stmt->bind_param(str_repeat("s", count($all_ma_hoc_phan)), ...$all_ma_hoc_phan);
    if ($stmt->execute()) {
        header("Location: manage_course.php");
        exit();
    } else {
        echo "Lỗi khi xóa học phần: " . $stmt->error;
    }
    $stmt->close();
}
?>
