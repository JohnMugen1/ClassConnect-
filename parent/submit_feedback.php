<?php
session_start();
require_once "../config/database.php";

// Ensure parent is logged in
if (!isset($_SESSION["parent_id"])) {
    header("Location: login.php");
    exit();
}

$parent_id = $_SESSION["parent_id"];

// Fetch student's ID from the database
$stmt = $conn->prepare("SELECT student_id FROM parents WHERE parent_id = ?");
$stmt->execute([$parent_id]);
$parent_data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$parent_data) {
    die("Error: Unable to retrieve student information.");
}

$student_id = $parent_data["student_id"];

$success_message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $feedback_type = $_POST["feedback_type"];
    $feedback_message = $_POST["feedback_message"];

    // Insert the feedback into the database
    $stmt = $conn->prepare("INSERT INTO parent_feedback (parent_id, student_id, feedback_type, feedback_message, created_at, status) 
                            VALUES (?, ?, ?, ?, NOW(), 'new')");
    $stmt->execute([$parent_id, $student_id, $feedback_type, $feedback_message]);

    $success_message = "Feedback submitted successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #ff9966, #ff5e62);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 420px;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        h2 {
            color: #333;
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            text-align: left;
            margin-top: 10px;
            color: #444;
        }
        select, textarea {
            width: calc(100% - 24px);
            padding: 12px;
            margin: 5px 0 15px 0;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: 0.3s;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        select:focus, textarea:focus {
            border-color: #ff5e62;
            outline: none;
        }
        textarea {
            height: 120px;
            resize: none;
            display: block;
        }
        .btn {
            width: 100%;
            background: #ff5e62;
            color: white;
            border: none;
            padding: 12px;
            margin-top: 15px;
            font-size: 18px;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn:hover {
            background: #e74c3c;
        }
        .back-btn {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #ff5e62;
            font-weight: bold;
            transition: 0.3s;
        }
        .back-btn:hover {
            color: #e74c3c;
        }
        .success-message {
            color: green;
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Submit Feedback</h2>

        <?php if ($success_message): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <form action="submit_feedback.php" method="POST">
            <label for="feedback_type">Feedback Type:</label>
            <select name="feedback_type" id="feedback_type">
                <option value="child">Child</option>
                <option value="school">School</option>
            </select>

            <label for="feedback_message">Feedback Message:</label>
            <textarea name="feedback_message" id="feedback_message" required></textarea>

            <button type="submit" class="btn">Submit Feedback</button>
        </form>

        <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
    </div>
</body>
</html>
