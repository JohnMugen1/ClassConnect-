<?php
require_once "../config/database.php";
session_start();

if (!isset($_SESSION["teacher_id"])) {
    header("Location: ../teacher/login.php");
    exit();
}

$teacher_id = $_SESSION["teacher_id"];
$view_type = isset($_GET['view_type']) ? $_GET['view_type'] : "";
$search_id = isset($_GET['search_id']) ? trim($_GET['search_id']) : "";

// Manage session-based active view
if (!isset($_SESSION["active_view"])) {
    $_SESSION["active_view"] = "";
}

if ($view_type) {
    $_SESSION["active_view"] = ($_SESSION["active_view"] === $view_type) ? "" : $view_type;
}

// Fetch students based on selected view type or search
$students = [];

if (!empty($search_id)) {
    $query = $conn->prepare("SELECT s.student_id, s.full_name, s.class, p.parent_id, p.full_name AS parent_name 
                             FROM students s 
                             LEFT JOIN parents p ON s.student_id = p.student_id 
                             WHERE s.student_id = ? AND s.teacher_id = ?");
    $query->execute([$search_id, $teacher_id]);
    $students = $query->fetchAll(PDO::FETCH_ASSOC);
} elseif ($_SESSION["active_view"] === "registered") {
    $query = $conn->prepare("SELECT s.student_id, s.full_name, s.class, p.parent_id, p.full_name AS parent_name 
                             FROM students s 
                             JOIN parents p ON s.student_id = p.student_id 
                             WHERE s.teacher_id = ?");
    $query->execute([$teacher_id]);
    $students = $query->fetchAll(PDO::FETCH_ASSOC);
} elseif ($_SESSION["active_view"] === "unregistered") {
    $query = $conn->prepare("SELECT s.student_id, s.full_name, s.class 
                             FROM students s 
                             LEFT JOIN parents p ON s.student_id = p.student_id 
                             WHERE p.student_id IS NULL AND s.teacher_id = ?");
    $query->execute([$teacher_id]);
    $students = $query->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌟 View Students - Smart System 📚</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <script>
        function confirmDelete(studentId) {
            if (confirm("🚨 Are you sure you want to delete this student? This action cannot be undone!")) {
                window.location.href = "delete_student.php?student_id=" + studentId;
            }
        }
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 800px;
            text-align: center;
        }
        h2 {
            color: #333;
            font-size: 24px;
        }
        h3 {
            color: #007bff;
            font-size: 20px;
            margin-top: 15px;
        }
        .search-container {
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 10px;
            width: 70%;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
        }
        button {
            padding: 10px 15px;
            border: none;
            background: #007bff;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 16px;
        }
        button:hover {
            background: #0056b3;
        }
        .btn-select {
            display: inline-block;
            padding: 10px;
            margin: 5px;
            background: #28a745;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-select:hover {
            background: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: linear-gradient(orange, black);
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background: #007bff;
            color: white;
        }
        tr:hover {
            background: #f1f1f1;
        }
        .logout-btn {
            position: absolute;
            top: 10px;
            right: 20px;
            padding: 10px 15px;
            background: #dc3545;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .logout-btn:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
<a href="dashboard.php" class="logout-btn">🚪 << Back to dashboard</a>
<div class="container">
    <h2>📚 View & Manage Students 🏫</h2>

    <div class="search-container">
        <form action="view_students.php" method="GET">
            <input type="text" name="search_id" placeholder="🔍 Enter Student ID..." value="<?= htmlspecialchars($search_id) ?>" required>
            <button type="submit">Search</button>
        </form>
    </div>

    <div>
        <a href="view_students.php?view_type=registered" class="btn-select">
            <?= ($_SESSION["active_view"] === "registered") ? "✅ Registered Students" : "📋 Registered Students" ?>
        </a>
        <a href="view_students.php?view_type=unregistered" class="btn-select">
            <?= ($_SESSION["active_view"] === "unregistered") ? "✅ Unregistered Students" : "📋 Unregistered Students" ?>
        </a>
    </div>

    <div>
        <?php if (!empty($students)): ?>
            <h3>
                <?php
                if (!empty($search_id)) {
                    $isRegistered = isset($students[0]['parent_id']) && !empty($students[0]['parent_id']);
                    echo $isRegistered ? "✅ Registered Student" : "🚫 Unregistered Student";
                }
                ?>
            </h3>

            <table>
                <thead>
                    <tr>
                        <th>🆔 Student ID</th>
                        <th>👨‍🎓 Name</th>
                        <th>🏢 Class</th>
                        <?php if ($_SESSION["active_view"] === "registered" || !empty($search_id)): ?>
                            <th>👨‍👩‍👧 Parent ID</th>
                            <th>👨‍👩‍👧 Parent Name</th>
                        <?php endif; ?>
                        <th>♻️ Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= htmlspecialchars($student["student_id"]) ?></td>
                            <td><?= htmlspecialchars($student["full_name"]) ?></td>
                            <td><?= htmlspecialchars($student["class"]) ?></td>
                            <?php if ($_SESSION["active_view"] === "registered" || !empty($search_id)): ?>
                                <td><?= htmlspecialchars($student["parent_id"] ?? "N/A") ?></td>
                                <td><?= htmlspecialchars($student["parent_name"] ?? "N/A") ?></td>
                            <?php endif; ?>
                            <td>
                                <button onclick="confirmDelete('<?= htmlspecialchars($student["student_id"]) ?>')">🗑️ Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>❌ No students found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
