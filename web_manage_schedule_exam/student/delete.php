<?php
include('config.php');

if (isset($_GET['msv']) && isset($_GET['ma_lich_thi'])) {
    $msv = $_GET['msv'];
    $ma_lich_thi = $_GET['ma_lich_thi'];

    $stmt = $conn->prepare("DELETE FROM dangkythi WHERE msv = ? AND ma_lich_thi = ?");
    $stmt->bind_param("ss", $msv, $ma_lich_thi);

    if ($stmt->execute()) {
        header("Location: home_student.php");
        exit();
    } else {
        echo "Lỗi khi xóa: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Thiếu tham số!";
}
?>