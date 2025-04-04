<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION["teacher_id"])) {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION["teacher_id"];

$stmt = $conn->prepare("SELECT pf.feedback_id, pf.parent_id, pf.student_id, pf.feedback_type, pf.feedback_message, pf.created_at, pf.status 
                        FROM parent_feedback pf
                        JOIN students s ON pf.student_id = s.student_id
                        WHERE s.teacher_id = ? 
                        ORDER BY pf.created_at DESC");
$stmt->execute([$teacher_id]);
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Feedback Dashboard</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fa;
            color: #2c3e50;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar */
        .teacher-sidebar {
            background: #004d00;
            color: white;
            width: 260px;
            height: 70vh;
            position: fixed;
            left: 15px;
            top: 15vh;
            transition: transform 0.3s ease-in-out;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }

        .teacher-nav {
            width: 100%;
        }

        .teacher-nav ul {
            list-style: none;
            padding: 0;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .teacher-nav ul li {
            margin-bottom: 15px;
            width: 100%;
        }

        .teacher-nav ul li a {
            display: block;
            padding: 12px;
            background: #ff9800;
            color: white;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
            width: 90%;
            text-align: center;
        }

        .teacher-nav ul li a:hover {
            background: #e67e22;
        }

        /* Main Content */
        .teacher-main {
            margin-left: 300px;
            padding: 40px;
            width: calc(100% - 300px);
            transition: margin-left 0.3s ease-in-out;
            overflow-y: auto;
            height: 100vh;
        }

        /* Logout Button - Fixed */
        .logout-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #d32f2f;
            color: white;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
            z-index: 1000;
        }

        .logout-btn:hover {
            background: #b71c1c;
            transform: scale(1.05);
        }

        /* Feedback Cards */
        .feedback-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 20px;
        }

        .feedback-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            border-left: 6px solid #2e7d32;
        }

        .feedback-header {
            font-weight: bold;
            font-size: 16px;
            color: #2c3e50;
        }

        .feedback-message {
            margin: 10px 0;
            color: #555;
        }

        .feedback-status {
            font-weight: bold;
            color: #d32f2f;
        }

        .status-viewed {
            color: green;
        }

        .mark-viewed-btn {
            background: #2e7d32;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .mark-viewed-btn:hover {
            background: #388e3c;
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            background: #ff9800;
            color: white;
            padding: 10px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            display: none;
        }

        .sidebar-toggle:hover {
            background: #f57c00;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .teacher-sidebar {
                transform: translateX(-100%);
                width: 250px;
                z-index: 1000;
            }

            .sidebar-visible {
                transform: translateX(0);
            }

            .teacher-main {
                margin-left: 0;
                padding: 20px;
                width: 100%;
            }

            .sidebar-toggle {
                display: block;
            }

            /* Responsive Logout Button */
            .logout-btn {
                top: 10px;
                right: 10px;
                padding: 8px 12px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<aside class="teacher-sidebar">
    <h2 class="teacher-welcome">📨 Feedback Dashboard</h2>
    <nav class="teacher-nav">
        <ul>
            <li><a href="dashboard.php">🏠 Home</a></li>
            <li><a href="../feedback/submit_feedback.php">📝 Submit Feedback</a></li>
        </ul>
    </nav>
</aside>

<!-- Sidebar Toggle Button -->
<button class="sidebar-toggle" onclick="toggleSidebar()">☰ Menu</button>

<!-- Logout Button -->
<a href="dashboard.php" class="logout-btn">🚪 << Back to dashboard</a>

<!-- Main Content -->
<main class="teacher-main">
    <h1>📢 Parent Feedback</h1>
    <div class="feedback-container">
        <?php if (empty($feedbacks)): ?>
            <p>No feedback available.</p>
        <?php else: ?>
            <?php foreach ($feedbacks as $feedback): ?>
                <div class="feedback-card">
                    <p class="feedback-header">👤 Parent ID: <?= htmlspecialchars($feedback['parent_id']); ?></p>
                    <p><strong>Type:</strong> <?= htmlspecialchars($feedback['feedback_type']); ?></p>
                    <p class="feedback-message"><strong>Message:</strong> <?= htmlspecialchars($feedback['feedback_message']); ?></p>
                    <p class="feedback-status <?= $feedback['status'] === 'Viewed' ? 'status-viewed' : ''; ?>">
                        <strong>Status:</strong> <?= htmlspecialchars($feedback['status']); ?>
                    </p>
                    <p><strong>Submitted on:</strong> <?= $feedback['created_at']; ?></p>
                    <form action="mark_feedback_as_viewed.php" method="POST">
                        <input type="hidden" name="feedback_id" value="<?= $feedback['feedback_id']; ?>">
                        <button type="submit" class="mark-viewed-btn">✅ Mark as Viewed</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<script>
    function toggleSidebar() {
        document.querySelector(".teacher-sidebar").classList.toggle("sidebar-visible");
    }
</script>

</body>
</html>
