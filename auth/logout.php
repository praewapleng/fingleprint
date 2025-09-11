<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the homepage
    header("Location: /fingerprint-attendance/public/index.php");
    exit;
} else {
    // If no user is logged in, redirect to the login page
    header("Location: /fingerprint-attendance/auth/login.php");
    exit;
}
?>