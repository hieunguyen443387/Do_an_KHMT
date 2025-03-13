<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles.css">
    <title>Trang đăng kí lịch thi</title>
    
</head>
<body onload = "myChoice()"> 

    <div class="footer">
        <p>Bạn đang đăng nhập với tên <u style="text-transform: uppercase;"><?php 
                    
                    $msv = $_SESSION['msv'];

                    $sql = "SELECT * FROM sinhvien WHERE msv = '$msv'";
                    $result = $conn->query($sql);
    
                    if ($result->num_rows > 0) {
                        
                        while($row = $result->fetch_assoc()) {
                            echo  $row["ho_dem"]. " " . $row["ten"] ;
                        }
                    } 
            ?></u> <a href="logout.php">(Thoát)</a></p></p>
    </div>

</body>
</html>
