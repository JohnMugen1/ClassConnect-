<?php
require_once "../config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacher_id = trim($_POST["teacher_id"]);
    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO teachers (teacher_id, full_name, email, password, is_approved) VALUES (?, ?, ?, ?, 0)");

    if ($stmt->execute([$teacher_id, $full_name, $email, $password])) {
        $message = "✅ Registration successful! Await admin approval.";
    } else {
        $message = "❌ Registration failed!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>👨‍🏫 Teacher Registration</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(green,orange);
            padding: 20px;
            position: relative;
        }

        /* Main Container */
        .container {
            width: 100%;
            max-width: 400px;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        h2 {
            font-size: 22px;
            color: orange;
            margin-bottom: 15px;
        }

        /* Message Styling */
        .message {
            font-size: 16px;
            color: green;
            margin-bottom: 10px;
        }

        /* Input Fields */
        .form-label {
            text-align: left;
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: white;
        }

        .form-input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        /* Buttons */
        .btn {
            width: 100%;
            padding: 12px;
            margin-top: 15px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .register-btn {
            background: #007bff;
            color: white;
        }

        .register-btn:hover {
            background: #0056b3;
        }

        .logout-btn {
            background: #dc3545;
            color: white;
        }

        .logout-btn:hover {
            background: #b22234;
        }

        /* Login Link */
        .register-link {
            margin-top: 15px;
            font-size: 14px;
        }

        .register-link a {
            color:rgb(89, 0, 255);
            text-decoration: none;
            font-weight: bold;
            font-family: Tahoma;
        }

        .register-link a:hover {
            text-decoration: underline;
            color: greenyellow;
        }

        /* Raindrop Effect */
        .rain-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        .raindrop {
            position: absolute;
            width: 2px;
            height: 10px;
            background: rgba(0, 0, 0, 0.1);
            animation: fall linear infinite;
        }

        @keyframes fall {
            from {
                transform: translateY(-100vh);
            }
            to {
                transform: translateY(100vh);
            }
        }

        /* Responsive */
        @media (max-width: 500px) {
            .container {
                max-width: 90%;
            }
        }
    </style>
</head>
<body>

    <div class="rain-container" id="rain"></div>

    <div class="container">
        <h2>👨‍🏫 Teacher Registration</h2>

        <?php if (!empty($message)) : ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="teacher_id" class="form-label">🆔 Teacher ID:</label>
            <input type="text" id="teacher_id" name="teacher_id" class="form-input" required>

            <label for="full_name" class="form-label">👤 Full Name:</label>
            <input type="text" id="full_name" name="full_name" class="form-input" required>

            <label for="email" class="form-label">📧 Email:</label>
            <input type="email" id="email" name="email" class="form-input" required>

            <label for="password" class="form-label">🔒 Password:</label>
            <input type="password" id="password" name="password" class="form-input" required>

            <button class="btn register-btn" type="submit">✅ Register</button>
        </form>

        <p class="register-link">🔹 Already have an account? <a href="login.php">Login here</a></p>

        <button class="btn logout-btn" onclick="location.href='../index.php'">🚪 Logout</button>
    </div>

    <script>
        function createRain() {
            let rainContainer = document.getElementById("rain");
            for (let i = 0; i < 100; i++) {
                let drop = document.createElement("div");
                drop.classList.add("raindrop");
                drop.style.left = Math.random() * 100 + "vw";
                drop.style.animationDuration = Math.random() * 1.5 + 0.5 + "s";
                drop.style.animationDelay = Math.random() * 2 + "s";
                rainContainer.appendChild(drop);
            }
        }

        createRain();
    </script>

</body>
</html>
