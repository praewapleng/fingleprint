// filepath: fingerprint-attendance/fingerprint-attendance/includes/functions.php

<?php
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function startSession() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function flashMessage($message, $type = 'success') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
}

function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        $type = $_SESSION['flash_type'];
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_type']);
        return ['message' => $message, 'type' => $type];
    }
    return null;
}
?>