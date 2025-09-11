<?php
session_start();
include("../config/database.php");

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username == "" || $password == "") {
    die("⚠️ กรุณากรอกชื่อผู้ใช้และรหัสผ่าน");
}

// ✅ แปลงรหัสผ่านเป็น SHA2-256 (แบบ binary) ให้ตรงกับ VARBINARY(64)
$hashedPassword = hash('sha256', $password, true);

// ✅ ดึง user ตาม username ก่อน
$sql = "SELECT * FROM logins WHERE username = ?";
$params = array($username);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if ($user && $user['password'] === $hashedPassword) {
    // เก็บ session
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['username']  = $user['username'];
    $_SESSION['role_type'] = $user['role_type'];

    // redirect ตาม role_type
    if ($user['role_type'] === "teacher") {
        header("Location: ../teacher/choose.php");
    } else {
        header("Location: ../student/view-schedule.php");
    }
    exit();
} else {
    echo "❌ ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
}
?>
