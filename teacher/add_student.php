<?php
require_once "../config/database.php";
session_start();

if (!isset($_SESSION["teacher_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = trim($_POST["student_id"]);
    $full_name = trim($_POST["full_name"]);
    $class = trim($_POST["class"]);
    $teacher_id = $_SESSION["teacher_id"];

    // Check if student already exists
    $check_stmt = $conn->prepare("SELECT student_id FROM students WHERE student_id = ?");
    $check_stmt->execute([$student_id]);

    if ($check_stmt->rowCount() > 0) {
        $message = "❌ Student ID already exists!";
    } else {
        // Insert new student
        $stmt = $conn->prepare("INSERT INTO students (student_id, full_name, class, teacher_id) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$student_id, $full_name, $class, $teacher_id])) {
            $message = "✅ Student added successfully!";
        } else {
            $message = "❌ Failed to add student.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>➕ Add Student</title>
  <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
  <div class="student-dashboard">
    <div class="river-animation"></div>
    <div class="student-form-container">
      <h2 class="form-title">➕ Add Student</h2>
      <?php if(isset($message)): ?>
        <p class="form-message"><?= $message ?></p>
      <?php endif; ?>
      <form method="POST" class="student-form">
        <div class="form-group">
          <label for="student_id" class="form-label">🆔 Student ID:</label>
          <input type="text" id="student_id" name="student_id" class="form-input" required>
        </div>
        <div class="form-group">
          <label for="full_name" class="form-label">👤 Full Name:</label>
          <input type="text" id="full_name" name="full_name" class="form-input" required>
        </div>
        <div class="form-group">
          <label for="class" class="form-label">🏫 Class:</label>
          <input type="text" id="class" name="class" class="form-input" required>
        </div>
        <button type="submit" class="btn submit-btn">✅ Add Student</button>
        <button class="btn logout-btn" onclick="location.href='dashboard.php'">🚪 Back to Dashboard</button>
      </form>
    </div>
  </div>
</body>
</html>
