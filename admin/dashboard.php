<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>

<div class="dashboard-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <h2>⚙️ Admin Panel</h2>
        <ul>
            <li><a href="approve_teachers.php">✅ Approve Teachers</a></li>
        </ul>
        <div class="logout-bottom">
            <a href="../logout.php" class="btn logout-btn">🚪 Logout</a>
        </div>
    </aside>

    <!-- Main Content with Enhanced Background -->
    <main class="dashboard-content">
        <div class="dashboard-header">
            <h1>👋 Welcome, Admin</h1>
            <p>Manage teacher approvals and oversee the system efficiently.</p>
        </div>

        <div class="dashboard-cards">
            <div class="card">
                <h3>🔍 Pending Approvals</h3>
                <p>Review and approve new teacher registrations.</p>
                <a href="approve_teachers.php" class="btn card-btn">Review Now</a>
            </div>
        </div>
    </main>
</div>

</body>
</html>
