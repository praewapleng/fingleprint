<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>หน้าหลัก</title>
    <!-- ✅ เพิ่ม Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Itim&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('student-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Itim', cursive; /* ✅ เปลี่ยนฟอนต์ */
        }
        .overlay {
            background: rgba(255,255,255,0.8);
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        h1 {
            margin-bottom: 40px;
            color: #333;
            font-size: 2.2em;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
        }
        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 20px;
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
            font-family: 'Itim', cursive; /* ✅ ปรับปุ่มให้ใช้ฟอนต์น่ารัก */
        }
        .main-btn:hover {
            background: #1565c0;
            transform: scale(1.05);
        }
        .side-btn-container {
            position: absolute;
            top: 50%;
            left: 350px;
            transform: translateY(-50%);
            z-index: 10;
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
    </style>
</head>
<body>
    <div class="side-btn-container">
        <a href="https://forms.gle/knn6K4ferAsRxQ377" target="_blank" class="side-btn">
            <img src="assessment.jpg" alt="Assessment" style="margin-top:40px; width:210px; height:200px; border-radius:20px; box-shadow:0 4px 12px rgba(0,0,0,0.3);">
        </a>
    </div>
    <div class="overlay">
        <h1>
            ระบบบันทึกข้อมูลการเข้าเรียนด้วยเครื่องอ่านลายนิ้วมือ <br>
            <span style="display:inline-block; width:100%; text-align:center;">ภาคเรียนที่ 1 ปีการศึกษา 2568</span>
        </h1>
        <div class="btn-group">
            <button class="main-btn" onclick="location.href='testhome.php'">ระบบผลการเข้าเรียน</button>
            <button class="main-btn" onclick="location.href='schedule.php'">ตารางเรียน</button>
            <button class="main-btn" onclick="location.href='indicator.php'">ตัวชี้วัดรายวิชา</button>
            <button class="main-btn" onclick="location.href='contact.php'">ติดต่อ</button>
        </div>
    </div>
</body>
</html>
