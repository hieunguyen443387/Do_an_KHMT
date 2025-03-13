<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="manage_add.css">
    <title>Trang quản lý lịch thi</title>
    
</head>
<body> 

    <div class="header">
        <div class="home">
            <a href="home_page.php"><h3>Trang quản lý lịch thi</h3></a>
        </div>
        <div class="user">
            <a href="login.php">Đăng nhập</a>
        </div>
    </div>
    <div class="container">
    <?php include('tag.php'); ?>  

        <div class="manage">
            <p>Quản lý</p>
            <ul>
                <li><a href="manage_student.php">Sinh viên</a></li>
                <li><a href="manage_teacher.php">Giảng viên</a></li>
                <li><a href="manage_class.php">Phòng thi</a></li>
                <li><a href="manage_course.php">Học phần</a></li>
                <li><a href="manage_schedule.php">Lịch thi</a></li>
            </ul>
        </div> 
    </div> 
    <div class="footer">
    <p>Bạn chưa đăng nhập  <a href="login.php">(Đăng nhập)</a></p>
    <p><a href="home_page.php">Trang chủ</a></p>
</div> 
    
</body>
</html>
