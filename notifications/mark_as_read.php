<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION["parent_id"])) {
    echo "error";
    exit();
}

$parent_id = $_SESSION["parent_id"];

// Mark all notifications as read for this parent
$update_stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE parent_id = ?");
if ($update_stmt->execute([$parent_id])) {
    echo "success";
} else {
    echo "error";
}
?>
