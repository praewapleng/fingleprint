<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>หน้าหลัก</title>
    <!-- ✅ Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Itim&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('student-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Itim', cursive;
        }
        .overlay {
            background: rgba(255,255,255,0.8);
            min-height: 100vh; 
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
            padding-bottom: 60px; 
        }
        h1 {
            margin: 60px 0 40px;
            color: #333;
            font-size: 2.2em;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
            text-align: center;
        }
        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 60px;
        }
        .main-btn {
            padding: 15px 40px;
            font-size: 1.2em;
            border: none;
            border-radius: 12px;
            background: #1976d2;
            color: #fff;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
            font-family: 'Itim', cursive;
        }
        .main-btn:hover {
            background: #1565c0;
            transform: scale(1.05);
        }
        /* ✅ ปุ่มฟอร์มอยู่ใต้ปุ่ม ติดต่อ */
.side-btn-container {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap; /* ช่วยให้เลื่อนลงได้เมื่อพื้นที่ไม่พอ */
}

.side-btn img {
    width: 210px;
    height: 200px;
    border-radius: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    transition: box-shadow 0.2s, transform 0.2s;
}

.side-btn img:hover {
    box-shadow: 0 6px 20px rgba(25,118,210,0.5);
    transform: scale(1.05);
}

/* ✅ บนมือถือ (จอแคบกว่า 600px) ให้ปุ่มฟอร์มเรียงเป็นแนวตั้ง */
@media (max-width: 600px) {
    .side-btn-container {
        flex-direction: column;
        align-items: center;
    }
}
    </style>
</head>
<body>
    <div class="overlay">
        <h1>
            ระบบบันทึกข้อมูลการเข้าเรียนด้วยเครื่องอ่านลายนิ้วมือ <br>
            <span style="display:inline-block; width:100%; text-align:center;">ภาคเรียนที่ 1 ปีการศึกษา 2568</span>
        </h1>
        <div class="btn-group">
            <button class="main-btn" onclick="location.href='public/index.php'">ระบบผลการเข้าเรียนสำหรับอาจารย์</button>
            <button class="main-btn" onclick="location.href='testhome.php'">ระบบผลการเข้าเรียนสำหรับนักศึกษา</button>
            <button class="main-btn" onclick="location.href='schedule.php'">ตารางเรียน</button>
            <button class="main-btn" onclick="location.href='indicator.php'">ตัวชี้วัดรายวิชา</button>
            <button class="main-btn" onclick="location.href='contact.php'">ติดต่อ</button>
        </div>

        <!-- ✅ ปุ่มฟอร์ม 2 ปุ่มอยู่ใต้ปุ่มติดต่อ -->
<div class="side-btn-container">
    <a href="https://forms.gle/c27Nofdc1QeMpeK99" target="_blank" class="side-btn">
        <img src="quality.jpg" alt="Quality Assessment">
    </a>
    <a href="https://forms.gle/DmHmN4aTXKZndre86" target="_blank" class="side-btn">
        <img src="satisfied.jpg" alt="Satisfied Assessment">
    </a>
</div>
    </div>
</body>
</html>
