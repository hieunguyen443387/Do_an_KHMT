<div class="footer">
    <p>Bạn đang đăng nhập với tên 
        <u style="text-transform: uppercase;"><?php 
                    
            $id_admin = $_SESSION['id_admin'];

            $sql = "SELECT * FROM admin WHERE id_admin = '$id_admin'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                
                while($row = $result->fetch_assoc()) {
                    echo  $row["ho_dem"]. " " . $row["ten"] ;
                }
            } 
        ?></u> <a href="logout.php">(Thoát)</a></p>
    <p><a href="home_admin.php">Trang chủ</a></p>
</div>
<script src="search.js"></script>