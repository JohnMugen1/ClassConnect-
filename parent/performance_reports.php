<!-- <?php
session_start();
include '../config/database.php';

// Ensure the parent is logged in
if (!isset($_SESSION['parent_id'])) {
    echo "<script>alert('Unauthorized access! Please log in.'); window.location.href='login.php';</script>";
    exit;
}

$parent_id = $_SESSION['parent_id'];

// Fetch student's details linked to the parent
$student_query = "SELECT s.student_id, s.full_name FROM students s
                  JOIN parents p ON s.student_id = p.student_id
                  WHERE p.parent_id = ?";
$stmt_student = $conn->prepare($student_query);
$stmt_student->execute([$parent_id]);
$student = $stmt_student->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    echo "<script>alert('No student linked to this account!'); window.location.href='dashboard.php';</script>";
    exit;
}

$student_id = $student['student_id'];
$student_name = $student['full_name'];

// Fetch performance reports
$report_query = "SELECT subject_name, subject_score, subject_rating, created_at FROM student_performance WHERE student_id = ? ORDER BY created_at DESC";
$stmt_report = $conn->prepare($report_query);
$stmt_report->execute([$student_id]);
$reports = $stmt_report->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Performance Reports 📊</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            width: 80%;
            max-width: 800px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 10px;
        }

        .no-report {
            color: red;
            font-size: 18px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #3498db;
            color: white;
        }

        .timestamp {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }

        .logout-btn {
            background: #e74c3c;
            padding: 12px;
            font-size: 18px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            width: 200px;
            text-align: center;
            font-weight: bold;
            margin-top: 15px;
            display: inline-block;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📖 Performance Report for <?php echo htmlspecialchars($student_name); ?></h2>

        <?p -->
