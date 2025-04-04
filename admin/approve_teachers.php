<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

// Approve or Reject Teacher
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacher_id = $_POST["teacher_id"];
    $action = $_POST["action"];

    if ($action == "approve") {
        $stmt = $conn->prepare("UPDATE teachers SET is_approved = 1 WHERE teacher_id = :teacher_id");
    } else { // Reject action
        $stmt = $conn->prepare("DELETE FROM teachers WHERE teacher_id = :teacher_id");
    }

    $stmt->execute(["teacher_id" => $teacher_id]);
    header("Location: approve_teachers.php");
    exit();
}

// Fetch pending teachers (those with is_approved = 0)
$stmt = $conn->query("SELECT * FROM teachers WHERE is_approved = 0");
$pending_teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Approve Teachers</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>

<div class="container">
    <h2 id='approve-title'>✅ Pending Teacher Approvals</h2>
    
    <?php if (empty($pending_teachers)): ?>
        <p class="no-data">🎉 No pending approvals!</p>
    <?php else: ?>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>📌 Teacher ID</th>
                    <th>👨‍🏫 Name</th>
                    <th>📧 Email</th>
                    <th>⚡ Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pending_teachers as $teacher): ?>
                    <tr>
                        <td><?= htmlspecialchars($teacher["teacher_id"]) ?></td>
                        <td><?= htmlspecialchars($teacher["full_name"]) ?></td>
                        <td><?= htmlspecialchars($teacher["email"]) ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher["teacher_id"]) ?>">
                                <button type="submit" name="action" value="approve" class="btn approve-btn">✔️ Approve</button>
                                <button type="submit" name="action" value="reject" class="btn reject-btn">❌ Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="logout-container">
        <button class="btn logout-btn" onclick="location.href='dashboard.php'">🚪 Back to Dashboard</button>
    </div>
</div>

</body>
</html>
