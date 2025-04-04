<?php
require_once "../config/database.php";
session_start();

if (!isset($_SESSION["parent_id"])) {
    header("Location: login.php");
    exit();
}

$parent_id = $_SESSION["parent_id"];

$stmt = $conn->prepare("SELECT feedback_text, created_at FROM feedback 
                        WHERE student_id = (SELECT student_id FROM parents WHERE parent_id = ?) 
                        ORDER BY created_at DESC");
$stmt->execute([$parent_id]);
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📢 Notifications</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.8), rgba(41, 128, 185, 0.8)), url('../assets/notifications-bg.jpg') no-repeat center center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden;
        }
        .notifications-wrapper {
            width: 90%;
            max-width: 600px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            height: 90vh;
            overflow: hidden;
        }
        .notifications-header {
            text-align: center;
            padding: 15px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            font-size: 22px;
            font-weight: bold;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .notifications-body {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }
        .notifications-list {
            width: 100%;
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .notifications-list li {
            background: rgba(52, 152, 219, 0.1);
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            font-size: 16px;
            color: #2c3e50;
        }
        .notifications-list li::before {
            content: "🔔";
            margin-right: 5px;
        }
        .no-notifications {
            color: red;
            font-weight: bold;
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
        }
        .back-btn {
            text-decoration: none;
            background: #e74c3c;
            color: white;
            padding: 12px;
            text-align: center;
            border-radius: 8px;
            font-weight: 600;
            display: block;
            position: sticky;
            bottom: 0;
            width: 100%;
        }
        .back-btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <div class="notifications-wrapper">
        <div class="notifications-header">📢 Notifications</div>

        <div class="notifications-body">
            <?php if (empty($feedbacks)) { ?>
                <p class="no-notifications">🚫 No new notifications.</p>
            <?php } else { ?>
                <ul class="notifications-list">
                    <?php foreach ($feedbacks as $feedback): ?>
                        <li>
                            📩 <?= htmlspecialchars($feedback["feedback_text"]) ?> 
                            <br> ⏳ <?= htmlspecialchars($feedback["created_at"]) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php } ?>
        </div>

        <a href="../parent/dashboard.php" class="back-btn">⬅️ Back to Dashboard</a>
    </div>
</body>
</html>
