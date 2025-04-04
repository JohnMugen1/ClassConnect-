<?php
require_once "../config/database.php";
session_start();

if (!isset($_SESSION["teacher_id"])) {
    header("Location: ../teacher/login.php");
    exit();
}
// Capture the active view type for redirection
$view_type = isset($_GET["view_type"]) ? $_GET["view_type"] : "";

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["student_id"])) {
    $student_id = $_GET["student_id"];

    try {
        $conn->beginTransaction();

        // Delete related records first
        $deleteFeedback = $conn->prepare("DELETE FROM feedback WHERE student_id = ?");
        $deleteFeedback->execute([$student_id]);

        $deleteNotifications = $conn->prepare("DELETE FROM notifications WHERE parent_id IN 
            (SELECT parent_id FROM parents WHERE student_id = ?)");
        $deleteNotifications->execute([$student_id]);

        $deletePerformance = $conn->prepare("DELETE FROM student_performance WHERE student_id = ?");
        $deletePerformance->execute([$student_id]);

        // Delete parent if exists
        $deleteParent = $conn->prepare("DELETE FROM parents WHERE student_id = ?");
        $deleteParent->execute([$student_id]);

        // Delete student
        $deleteStudent = $conn->prepare("DELETE FROM students WHERE student_id = ?");
        $deleteStudent->execute([$student_id]);

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollBack();
    }
}

// Redirect back to the same view type after deletion
header("Location: view_students.php?view_type=" . urlencode($view_type));
exit();
?>
