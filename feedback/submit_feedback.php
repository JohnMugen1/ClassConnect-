<?php
require_once "../config/database.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST["student_id"];
    $feedback_text = $_POST["feedback_text"];
    $teacher_id = $_SESSION["teacher_id"];

    // Check if student exists
    $check_stmt = $conn->prepare("SELECT student_id FROM students WHERE student_id = ?");
    $check_stmt->execute([$student_id]);

    if ($check_stmt->rowCount() == 0) {
        $message = "❌ Student with ID $student_id is not registered.";
        $msg_class = "error";
    } else {
        // Insert feedback if student exists
        $stmt = $conn->prepare("INSERT INTO feedback (student_id, teacher_id, feedback_text) VALUES (?, ?, ?)");
        if ($stmt->execute([$student_id, $teacher_id, $feedback_text])) {
            $message = "✅ Feedback submitted successfully!";
            $msg_class = "success";
        } else {
            $message = "❌ Failed to submit feedback.";
            $msg_class = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>📝 Submit Feedback</title>
  <link rel="stylesheet" href="../assets/styles.css">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: 'Poppins', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    }
    .feedback-container {
      background: rgba(255, 255, 255, 0.1);
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      max-width: 400px;
      width: 100%;
      text-align: center;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
    h2 {
      font-size: 24px;
      margin-bottom: 10px;
      color: #ffcc00;
    }
    .feedback-form {
      display: flex;
      flex-direction: column;
      width: 100%;
      gap: 15px;
    }
    .form-group {
      width: 100%;
      text-align: left;
    }
    .form-label {
      font-weight: bold;
      color: #ffcc00;
      margin-bottom: 5px;
      display: block;
    }
    .form-input {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: none;
      background: rgba(255, 255, 255, 0.2);
      color: #fff;
      outline: none;
      transition: 0.3s;
    }
    .form-input:focus {
      border: 2px solid #ffcc00;
      background: rgba(255, 255, 255, 0.3);
    }
    .btn {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      font-weight: bold;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: 0.3s;
    }
    .btn-submit {
      background: #ffcc00;
      color: #000;
    }
    .btn-submit:hover {
      background: #e6b800;
    }
    .btn-back {
      background: #ff5733;
      color: white;
    }
    .btn-back:hover {
      background: #e64a19;
    }
    .message-box {
      padding: 10px;
      border-radius: 8px;
      font-weight: bold;
      margin-bottom: 15px;
      text-align: center;
    }
    .success {
      background: rgba(46, 204, 113, 0.2);
      color: #2ecc71;
    }
    .error {
      background: rgba(231, 76, 60, 0.2);
      color: #e74c3c;
    }
  </style>
</head>
<body>
  <div class="feedback-container">
     <h2>📝 Submit Feedback</h2>
     <?php if(isset($message)): ?>
       <p class="message-box <?= $msg_class ?>"><?= $message ?></p>
     <?php endif; ?>
     <form method="POST" class="feedback-form">
       <div class="form-group">
         <label for="student_id" class="form-label">🆔 Student ID:</label>
         <input type="text" id="student_id" name="student_id" class="form-input" required>
       </div>
       <div class="form-group">
         <label for="feedback_text" class="form-label">💬 Feedback:</label>
         <textarea id="feedback_text" name="feedback_text" rows="4" class="form-input" required></textarea>
       </div>
       <button type="submit" class="btn btn-submit">Submit</button>
       <button type="button" class="btn btn-back" onclick="location.href='../teacher/dashboard.php'">🚪 Back to Dashboard</button>
     </form>
  </div>
</body>
</html>
