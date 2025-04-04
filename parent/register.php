<?php
require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $student_id = trim($_POST["student_id"]);

    $stmt = $conn->prepare("SELECT student_id FROM students WHERE student_id = ?");
    $stmt->execute([$student_id]);

    if ($stmt->rowCount() == 0) {
        $message = "❌ The student ID you entered does not exist. Please check and try again.";
    } else {
        $stmt = $conn->prepare("INSERT INTO parents (full_name, email, password, student_id) VALUES (?, ?, ?, ?)");

        if ($stmt->execute([$full_name, $email, $password, $student_id])) {
            $message = "✅ Registration successful! You can now log in.";
        } else {
            $message = "❌ Failed to register. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Registration</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #87CEEB, #1E90FF);
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .title {
            font-size: 22px;
            margin-bottom: 15px;
        }
        .form-label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            color: blue;
            font-weight: bold;
        }
        .form-input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            color:#000;
        }
        .btn {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .register-btn {
            background-color: #28a745;
            color: white;
        }
        .logout-btn {
            background-color: #dc3545;
            color: white;
        }
        .message {
            margin: 10px 0;
            font-weight: bold;
            color: orange;
        }
        .link {
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="title">👨‍👩‍👦 Parent Registration</h2>

        <?php if (!empty($message)) : ?>
            <p class="message"> <?= $message ?> </p>
            <?php if ($message === "✅ Registration successful! You can now log in.") : ?>
                <a href="login.php"><button class="btn register-btn">➡ Go to Login</button></a>
            <?php endif; ?>
        <?php endif; ?>

        <form method="POST">
            <label for="full_name" class="form-label">👤 Full Name:</label>
            <input type="text" id="full_name" name="full_name" class="form-input" required>

            <label for="email" class="form-label">📧 Email:</label>
            <input type="email" id="email" name="email" class="form-input" required>

            <label for="password" class="form-label">🔒 Password:</label>
            <input type="password" id="password" name="password" class="form-input" required>

            <label for="student_id" class="form-label">🎓 Student ID:</label>
            <input type="text" id="student_id" name="student_id" class="form-input" required>

            <button class="btn register-btn" type="submit">✅ Register</button>
        </form>

        <p class="link">🔹 Already have an account? <a href="login.php">Login here</a></p>

        <button class="btn logout-btn" onclick="location.href='../index.php'">🚪 Logout</button>
    </div>
</body>
</html>
