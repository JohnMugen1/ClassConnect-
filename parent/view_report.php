<?php
// Start session securely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    session_regenerate_id(true);
}

require_once "../config/database.php";

// Redirect unauthorized users early
if (!isset($_SESSION["parent_id"])) {
    header("Location: login.php");
    exit();
}

$parent_id = $_SESSION["parent_id"];

// Fetch parent and student details
$stmt = $conn->prepare("SELECT parents.full_name AS parent_name, students.full_name AS student_name, students.student_id 
                        FROM parents 
                        JOIN students ON parents.student_id = students.student_id 
                        WHERE parents.parent_id = ?");
$stmt->execute([$parent_id]);
$parent = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$parent) {
    echo "<p style='text-align: center; font-size: 18px; color: red;'>Error: Parent data not found!</p>";
    exit();
}

$student_id = $parent["student_id"];
$student_name = $parent["student_name"];

// Fetch performance reports
$report_query = "SELECT subject_name, subject_score, subject_rating, term, class, year, created_at 
                 FROM student_performance 
                 WHERE student_id = ? 
                 ORDER BY year DESC, term DESC, created_at DESC";
$stmt_report = $conn->prepare($report_query);
$stmt_report->execute([$student_id]);
$reports = $stmt_report->fetchAll(PDO::FETCH_ASSOC);

// Group reports by term, class, and year
$grouped_reports = [];
foreach ($reports as $record) {
    $title = "EXAMINATION REPORT - TERM " . htmlspecialchars($record['term']) . 
         ", CLASS " . htmlspecialchars($record['class']) . 
         ", YEAR " . htmlspecialchars($record['year']);
    $grouped_reports[$title][] = $record;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Performance Report</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f4f4f4;
            height: 100vh;
        }
        .report-container {
            width: 90%;
            max-width: 900px;
            background: white;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            padding: 20px;
            text-align: center;
            overflow-y: auto;
        }
        h2 {
            color: #333;
        }
        .message {
            font-size: 18px;
            color: red;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .back-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 15px;
            background: #ff5733;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }
        .back-btn:hover {
            background: #d43f00;
        }
    </style>
</head>
<body>
    <div class="report-container">
        <h2>Performance Report for <?= htmlspecialchars($student_name) ?></h2>

        <?php if (!empty($grouped_reports)): ?>
            <?php foreach ($grouped_reports as $title => $records): ?>
                <h3><?= $title ?></h3>
                <table>
                    <tr>
                        <th>Subject</th>
                        <th>Score</th>
                        <th>Rating</th>
                        <th>Term</th>
                        <th>Class</th>
                        <th>Year</th>
                    </tr>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><?= htmlspecialchars($record['subject_name']) ?></td>
                            <td><?= htmlspecialchars($record['subject_score']) ?></td>
                            <td><?= htmlspecialchars($record['subject_rating']) ?></td>
                            <td><?= htmlspecialchars($record['term']) ?></td>
                            <td><?= htmlspecialchars($record['class']) ?></td>
                            <td><?= htmlspecialchars($record['year']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="message">No performance records found.</p>
        <?php endif; ?>

        <a href="dashboard.php" class="back-btn">⬅ Go Back</a>
    </div>
</body>
</html>
