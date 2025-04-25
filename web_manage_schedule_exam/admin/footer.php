<?php 

include('config.php');

?>

<div class="footer">
    <p>Bạn đang đăng nhập với tên 
        <?php
            if (isset($_SESSION['id_admin'])) {
                $id_admin = $_SESSION['id_admin'];
            
                // Truy vấn để lấy tên của admin
                $sql = "SELECT * FROM admin WHERE id_admin = ?";
                $stmt = $conn->prepare($sql); 
                $stmt->bind_param("s", $id_admin); 
                $stmt->execute();
                $result = $stmt->get_result();
            
                if ($result->num_rows > 0) {
                    // Hiển thị tên admin
                    while($row = $result->fetch_assoc()) {
                        echo "<u style='text-transform: uppercase;'>" . $row["ho_dem"] . " " . $row["ten"] . "</u>";
                    }
                } else {
                    echo "Không tìm thấy thông tin admin!";
                }
                $stmt->close(); 
            } else {
                echo "Chưa đăng nhập!";
            }
        ?>
    </p>
    <p><a href="logout.php">(Thoát)</a></p>
    <p><a href="home_admin.php">Trang chủ</a></p>
</div>
<script src="search.js"></script>
