<?php
include '../config/database.php';

$studentExists = false;
$showForm = false;
$error_message = "";
$student_id = "";
$total_subjects = "";
$term = "";
$class = "";
$year = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['generate_form'])) {
    $student_id = intval($_POST['student_id']); 
    $total_subjects = intval($_POST['total_subjects']);
    $term = $_POST['term'];
    $class = $_POST['class'];
    $year = $_POST['year'];

    $check_query = "SELECT * FROM students WHERE student_id = ?";
    $stmt_check = $conn->prepare($check_query);
    $stmt_check->execute([$student_id]);
    $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        $error_message = "⚠️ Student ID not found. Please enter a valid student ID.";
    } else {
        $studentExists = true;
        $showForm = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_performance'])) {
    $student_id = intval($_POST['student_id']);
    $total_subjects = intval($_POST['total_subjects']);
    $term = $_POST['term'];
    $class = $_POST['class'];
    $year = $_POST['year'];

    $query = "INSERT INTO student_performance (student_id, subject_name, subject_score, subject_rating, term, class, year) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    for ($i = 0; $i < $total_subjects; $i++) {
        $subject_name = $_POST['subject_name'][$i];
        $subject_score = intval($_POST['subject_score'][$i]);
        $subject_rating = $_POST['subject_rating'][$i];

        $stmt->execute([$student_id, $subject_name, $subject_score, $subject_rating, $term, $class, $year]);
    }

    echo "<script>alert('✅ Performance data saved successfully!'); window.location.href='update_performance.php';</script>";
    exit;
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>🎓 Update Performance</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #74ebd5, #ACB6E5);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            overflow: hidden;
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            width: 450px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }

        h2 {
            color: #333;
            margin-bottom: 15px;
            font-weight: 600;
        }

        label {
            font-weight: 600;
            color: #444;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[readonly] {
            background: #e9e9e9;
            color: #555;
            cursor: not-allowed;
        }

        button {
            background: #3498db;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
            position: relative;
            z-index: 10;
        }

        button:hover {
            background: #217dbb;
        }

        .error {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .form-container {
            max-height: 60vh;
            overflow-y: auto;
            padding-right: 5px;
        }

        .logout {
            position: fixed;
            bottom: 10px;
            right: 10px;
            background:rgb(177, 60, 231);
            color: white;
            padding: 8px 15px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration-line: none;
        }

        .logout:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📚 Update Student Performance</h2>

        <?php if (!empty($error_message)) echo "<p class='error'>$error_message</p>"; ?>

        <form method='POST' action='update_performance.php'>
            <label>🆔 Student ID:</label>
            <input type='number' name='student_id' required value="<?php echo htmlspecialchars($student_id); ?>" <?php echo $showForm ? 'readonly' : ''; ?>>

            <label>📆 Term:</label>
            <input type='text' name='term' required value="<?php echo htmlspecialchars($term); ?>" <?php echo $showForm ? 'readonly' : ''; ?>>

            <label>🏫 Class:</label>
            <input type='text' name='class' required value="<?php echo htmlspecialchars($class); ?>" <?php echo $showForm ? 'readonly' : ''; ?>>

            <label>📅 Year:</label>
            <input type='number' name='year' required value="<?php echo htmlspecialchars($year); ?>" <?php echo $showForm ? 'readonly' : ''; ?>>

            <label>📊 Total Subjects:</label>
            <input type='number' name='total_subjects' required value="<?php echo htmlspecialchars($total_subjects); ?>" <?php echo $showForm ? 'readonly' : ''; ?>>

            <?php if ($showForm) { ?>
                <div class='form-container'>
                    <?php for ($i = 0; $i < $total_subjects; $i++) { ?>
                        <label>📘 Subject <?php echo $i + 1; ?>:</label>
                        <input type='text' name='subject_name[]' required>
                        <label>📈 Score:</label>
                        <input type='number' name='subject_score[]' required>
                        <label>⭐ Rating:</label>
                        <select name='subject_rating[]' required>
                            <option value="EXCELLENT">EXCELLENT</option>
                            <option value="GOOD">GOOD</option>
                            <option value="AVERAGE">AVERAGE</option>
                            <option value="POOR">POOR</option>
                        </select>
                    <?php } ?>
                </div>
                <button type='submit' name='submit_performance'>✅ Save Performance</button>
            <?php } else { ?>
                <button type='submit' name='generate_form'>🎯 Generate Form</button>
            <?php } ?>
        </form>
    </div>

    <a href="dashboard.php" class="logout"><< Back to dashboard</a>
</body>
</html>
