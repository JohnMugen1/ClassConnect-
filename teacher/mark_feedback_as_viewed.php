<?php
session_start();
require_once "../config/database.php";

// Ensure teacher is logged in
if (!isset($_SESSION["teacher_id"])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['feedback_id'])) {
    $feedback_id = $_POST['feedback_id'];

    // Update feedback status to 'viewed'
    $stmt = $conn->prepare("UPDATE parent_feedback SET status = 'viewed' WHERE feedback_id = ?");
    $stmt->execute([$feedback_id]);

    header("Location: feedback_dashboard.php");
    exit();
}
?>
