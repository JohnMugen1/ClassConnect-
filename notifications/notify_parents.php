<!-- <?php
// require_once "../config/database.php";

// $message = "New feedback available!";
// $stmt = $conn->prepare("INSERT INTO notifications (parent_id, message) SELECT parent_id, ? FROM parents");
// $stmt->execute([$message]);

// echo "Notifications sent!";
// ?> -->

<?php
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = "New feedback available!";
    $stmt = $conn->prepare("INSERT INTO notifications (parent_id, message) SELECT parent_id, ? FROM parents");
    if ($stmt->execute([$message])) {
        $notification_status = "✅ Notifications sent successfully!";
    } else {
        $notification_status = "❌ Failed to send notifications.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📢 Notify Parents</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #2a5298, #1e3c72);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .notify-container {
            background: rgba(0, 0, 0, 0.85);
            color: #fff;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
            width: 90%;
            max-width: 450px;
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
            transition: transform 0.3s ease, border 0.3s ease;
        }
        .notify-container:hover {
            transform: scale(1.02);
            border-color: #ffcc00;
        }
        h2 {
            margin-bottom: 15px;
            font-size: 24px;
            text-transform: uppercase;
        }
        .notify-btn {
            width: 100%;
            padding: 12px;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            background-color: #ffcc00;
            color: #000;
            transition: background 0.3s ease, transform 0.2s ease;
            text-transform: uppercase;
        }
        .notify-btn:hover {
            background-color: #e6b800;
            transform: scale(1.05);
        }
        .status-message {
            font-size: 16px;
            margin-top: 15px;
            font-weight: bold;
            color: #1abc9c;
        }
        .loading {
            display: none;
            font-size: 16px;
            color: #ffcc00;
            margin-top: 15px;
        }
        @media (max-width: 480px) {
            .notify-container {
                padding: 25px;
                width: 95%;
            }
            .notify-btn {
                font-size: 16px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<div class="notify-container">
    <h2>📢 Notify Parents</h2>
    <form method="POST" onsubmit="showLoading()">
        <button type="submit" class="notify-btn">Send Notifications</button>
    </form>
    <p class="loading" id="loadingText">⏳ Sending notifications...</p>
    <?php if (isset($notification_status)): ?>
        <p class="status-message"><?= $notification_status ?></p>
    <?php endif; ?>
    <br>
    <button onclick="location.href='../teacher/dashboard.php'" class="notify-btn">🚪 Back to Dashboard</button>
</div>

<script>
    function showLoading() {
        document.getElementById('loadingText').style.display = 'block';
    }
</script>

</body>
</html>



