<?php
// register.php

require_once '../config/database.php';
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']); // 'teacher' or 'student'

    // Validate input
    if (empty($username) || empty($password) || empty($role)) {
        $error = "Please fill in all fields.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Create a new user in the database
        $sql = "INSERT INTO users (username, password, usertype) VALUES (?, ?, ?)";
        $params = array($username, $hashed_password, $role);
        $stmt = sqlsrv_query($conn, $sql, $params);
        
        if ($stmt === false) {
            $error = "Registration failed: " . print_r(sqlsrv_errors(), true);
        } else {
            header("Location: login.php?success=Registration successful. Please log in.");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="teacher">Teacher</option>
                <option value="student">Student</option>
            </select>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</body>
</html>