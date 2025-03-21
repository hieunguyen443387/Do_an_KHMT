<?php 
    include('config.php'); 
    session_start();
    if (!isset($_SESSION['id_admin'])) {
        header("Location: ../admin/login.php");
        exit();
    }

    $id_admin = $_SESSION['id_admin'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="manage_add.css">
    <script src="select_all.js"></script>
    <script src="search.js"></script>
    <title>Trang quản lý lịch thi</title>
    
</head>
<body> 

    <div class="header">
        <div class="home">
            <a href="home_admin.php"><h3>Trang quản lý lịch thi</h3></a>
        </div>
        <div class="user" onclick="myFunction()">
            <span style="text-transform: uppercase;"><?php 
                    
                    $id_admin = $_SESSION['id_admin'];

                    $sql = "SELECT * FROM admin WHERE id_admin = '$id_admin'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        
                        while($row = $result->fetch_assoc()) {
                            echo  $row["ho_dem"]. " " . $row["ten"] ;
                        }
                    } 
                ?>

            </span>
            <div class="dropdown-content" id="myDropdown">
                <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i>Thoát</a>
            </div>
        </div>
    </div>
    
    <script>
        function myFunction() {
            document.getElementById("myDropdown").classList.toggle("show");
        }
        
        window.onclick = function (e) {
            var user = document.querySelector(".user");
            var myDropdown = document.getElementById("myDropdown");
        
            if (!user.contains(e.target) && !myDropdown.contains(e.target)) {
                myDropdown.classList.remove("show");
            }
        };   
        
    </script>
</body>
</html>
