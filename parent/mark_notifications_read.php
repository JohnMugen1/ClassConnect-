<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION["parent_id"])) {
    echo "Unauthorized";
    exit();
}

$parent_id = $_SESSION["parent_id"];

$stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE parent_id = ?");
$stmt->execute([$parent_id]);

echo "success";
?>
