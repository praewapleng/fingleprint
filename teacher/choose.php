<?php
session_start();
// ตรวจสอบว่ามีการ login หรือยัง
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เมนูเลือกการทำงาน</title>
    <!-- ฟอนต์ -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600&display=swap" rel="stylesheet">
    <!-- ไอคอน -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background: linear-gradient(to bottom right, #e3f2fd, #ffffff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .menu-container {
            text-align: center;
        }
        h1 {
            font-size: 28px;
            color: #0d47a1;
            margin-bottom: 40px;
        }
        .menu-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: #2196f3;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            padding: 15px 30px;
            border: none;
            border-radius: 12px;
            margin: 15px;
            text-decoration: none;
            box-shadow: 0px 6px 15px rgba(0,0,0,0.2);
            transition: all 0.3s ease-in-out;
        }
        .menu-btn i {
            font-size: 20px;
        }
        .menu-btn:hover {
            background: #1976d2;
            transform: translateY(-5px);
            box-shadow: 0px 12px 20px rgba(0,0,0,0.25);
        }
    </style>
</head>
<body>
    <div class="menu-container">
        <h1>กรุณาเลือกการทำงาน</h1>
        
        <!-- ปุ่มสรุปผลการเข้าเรียน -->
        <a href="../testhome.php" class="menu-btn">
            <i class="fa-solid fa-chart-line"></i> สรุปผลการเข้าเรียน
        </a>

        <!-- ปุ่มเพิ่ม/ลดรายวิชา -->
        <a href="manage-subjects.php" class="menu-btn">
            <i class="fa-solid fa-book-open"></i> เพิ่ม/ลดรายวิชา
        </a>
    </div>
</body>
</html>
