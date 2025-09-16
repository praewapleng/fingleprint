<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เข้าสู่ระบบ</title>
    <!-- ฟอนต์ Google -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600&display=swap" rel="stylesheet">
    <!-- ไอคอน -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box; /* ✅ ป้องกัน input ล้นกรอบ */
        }
        body {
            font-family: 'Kanit', sans-serif;
            background: linear-gradient(to bottom right, #e3f2fd, #ffffff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0px 8px 20px rgba(0,0,0,0.1);
            width: 350px;
            text-align: center;
        }
        .login-container h1 {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #0d47a1;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        label {
            font-weight: 500;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }
        .form-input {
            width: 100%;   /* ✅ ครอบพอดีกรอบ */
            padding: 12px 15px;
            border-radius: 10px;
            border: 1px solid #ccc;
            outline: none;
            font-size: 15px;
            transition: all 0.3s ease-in-out;
        }
        .form-input:focus {
            border-color: #2196f3;
            box-shadow: 0px 0px 8px rgba(33,150,243,0.3);
        }
        button {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: none;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            background: #2196f3;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #1976d2;
        }
        .icon-input {
            position: relative;
        }
        .icon-input i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }
        .icon-input input {
            padding-left: 40px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h1><i class="fa-solid fa-user-lock"></i> เข้าสู่ระบบ</h1>
        <form id="loginForm" action="../auth/login.php" method="POST">
            
            <div class="form-group">
                <label for="username">ชื่อผู้ใช้</label>
                <div class="icon-input">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" id="username" name="username" class="form-input" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password">รหัสผ่าน</label>
                <div class="icon-input">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" id="password" name="password" class="form-input" required>
                </div>
            </div>

            <button type="submit"><i class="fa-solid fa-right-to-bracket"></i> LOGIN </button>
        </form>
    </div>
        </a>
    </div>
</body>
</html>
