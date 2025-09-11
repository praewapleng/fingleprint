<?php
// Include database configuration
require_once '../config/database.php';

// Initialize variables
$subject_name = '';
$description = '';
$schedule = '';
$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject_name = trim($_POST['subject_name']);
    $description = trim($_POST['description']);
    $schedule = trim($_POST['schedule']);

    // Validate input
    if (empty($subject_name) || empty($description) || empty($schedule)) {
        $error = 'Please fill in all fields.';
    } else {
        // Prepare SQL statement
        $sql = "INSERT INTO subjects (subject_name, description, schedule) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $subject_name, $description, $schedule);

        // Execute and check if successful
        if ($stmt->execute()) {
            header("Location: manage-subjects.php?success=Subject created successfully.");
            exit();
        } else {
            $error = 'Error creating subject. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Create Subject</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Create New Subject</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="create-subject.php" method="POST">
            <label for="subject_name">Subject Name:</label>
            <input type="text" id="subject_name" name="subject_name" value="<?php echo htmlspecialchars($subject_name); ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($description); ?></textarea>

            <label for="schedule">Schedule:</label>
            <input type="text" id="schedule" name="schedule" value="<?php echo htmlspecialchars($schedule); ?>" required>

            <button type="submit">Create Subject</button>
        </form>
    </div>
</body>
</html>