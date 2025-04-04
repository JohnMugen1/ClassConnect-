<?php
session_start();
require_once "../config/database.php";

// Ensure parent is logged in
if (!isset($_SESSION["parent_id"])) {
    header("Location: login.php");
    exit();
}

$parent_id = $_SESSION["parent_id"];

// Retrieve feedback submitted by this parent
$stmt = $conn->prepare("SELECT * FROM parent_feedback WHERE parent_id = ? ORDER BY created_at DESC");
$stmt->execute([$parent_id]);
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📩 Your Feedback</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 600px;
            text-align: center;
            margin-top: 40px;
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
        .feedback-list {
            max-height: 400px; /* Limit height */
            overflow-y: auto; /* Enable scrolling */
            padding-right: 10px; /* Prevent cutoff */
        }
        .feedback-card {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            text-align: left;
            transition: 0.3s;
        }
        .feedback-card:hover {
            transform: scale(1.02);
        }
        .feedback-card strong {
            color: #333;
        }
        .status {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            margin-top: 5px;
        }
        .status.new {
            background: #ffeb3b;
            color: #333;
        }
        .status.reviewed {
            background: #4caf50;
            color: white;
        }
        .back-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background: #4facfe;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 8px;
            transition: 0.3s;
        }
        .back-btn:hover {
            background: #00c6ff;
        }
        /* Custom scrollbar */
        .feedback-list::-webkit-scrollbar {
            width: 6px;
        }
        .feedback-list::-webkit-scrollbar-thumb {
            background: #00c6ff;
            border-radius: 10px;
        }
        .feedback-list::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📩 Your Feedback Submissions</h2>

        <div class="feedback-list">
            <?php if (count($feedbacks) > 0): ?>
                <?php foreach ($feedbacks as $feedback): ?>
                    <div class="feedback-card">
                        <strong>Type:</strong> 
                        <?= ($feedback['feedback_type'] == 'child' ? '👶' : '🏫') . " " . htmlspecialchars($feedback['feedback_type']); ?><br>
                        <strong>Message:</strong> <?= nl2br(htmlspecialchars($feedback['feedback_message'])); ?><br>
                        <strong>Status:</strong>
                        <span class="status <?= $feedback['status'] == 'new' ? 'new' : 'reviewed' ?>">
                            <?= $feedback['status'] == 'new' ? '🟡 New' : '✅ Reviewed'; ?>
                        </span><br>
                        <strong>Submitted on:</strong> 📅 <?= date("F j, Y, g:i a", strtotime($feedback['created_at'])); ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No feedback submissions found.</p>
            <?php endif; ?>
        </div>

        <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
    </div>
</body>
</html>
