<?php
// edit-subject.php

require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

session_start();

if (!isTeacherLoggedIn()) {
    header("Location: ../auth/login.php");
    exit();
}

$subjectId = $_GET['id'] ?? null;
$subject = null;

if ($subjectId) {
    $stmt = $pdo->prepare("SELECT * FROM subjects WHERE id = :id");
    $stmt->execute(['id' => $subjectId]);
    $subject = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $schedule = $_POST['schedule'] ?? '';

    if ($subject) {
        $stmt = $pdo->prepare("UPDATE subjects SET name = :name, description = :description, schedule = :schedule WHERE id = :id");
        $stmt->execute([
            'name' => $name,
            'description' => $description,
            'schedule' => $schedule,
            'id' => $subjectId
        ]);
        header("Location: manage-subjects.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขวิชา</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <div class="container">
        <h1>แก้ไขวิชา</h1>
        <?php if ($subject): ?>
            <form method="POST">
                <label for="name">ชื่อวิชา:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($subject['name']) ?>" required>

                <label for="description">คำอธิบาย:</label>
                <textarea id="description" name="description" required><?= htmlspecialchars($subject['description']) ?></textarea>

                <label for="schedule">ตารางเรียน:</label>
                <input type="text" id="schedule" name="schedule" value="<?= htmlspecialchars($subject['schedule']) ?>" required>

                <button type="submit">บันทึกการเปลี่ยนแปลง</button>
            </form>
        <?php else: ?>
            <p>ไม่พบวิชาที่ต้องการแก้ไข</p>
        <?php endif; ?>
    </div>
</body>
</html>