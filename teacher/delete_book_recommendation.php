<?php
session_start();
require_once "../config/database.php";

$teacher_id = $_SESSION["teacher_id"];
$book_id = $_GET["id"];

$stmt = $conn->prepare("DELETE FROM book_recommendations WHERE id = ? AND teacher_id = ?");
$stmt->execute([$book_id, $teacher_id]);

header("Location: teacher_recommendations.php");
exit();
?>
