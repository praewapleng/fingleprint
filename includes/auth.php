<?php
session_start();

function login($username, $password) {
    include '../config/database.php';
    
    $sql = "SELECT id, role FROM users WHERE username = ? AND password = ?";
    $params = array($username, $password);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $role);
        $stmt->fetch();
        $_SESSION['user_id'] = $id;
        $_SESSION['role'] = $role;
        return true;
    } else {
        return false;
    }

    $stmt->close();
    $conn->close();
}

function register($username, $password, $role) {
    include '../config/database.php';
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

function logout() {
    session_start();
    session_destroy();
    header("Location: ../public/index.php");
    exit();
}
?>