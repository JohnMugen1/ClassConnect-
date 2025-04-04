<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>🏫 Smart Parent-Teacher System</title>
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: #f0f2f5;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 500px;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            font-size: 22px;
            color: blueviolet;
            margin-bottom: 15px;
        }

        .section {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            margin: 15px 0;
        }

        h2 {
            font-size: 18px;
            color: #444;
            margin-bottom: 10px;
        }

        select, .btn {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        .btn {
            background: #007bff;
            color: white;
            cursor: pointer;
            transition: 0.3s;
            border: none;
        }

        .btn:hover {
            background: #0056b3;
        }

        /* Centering login buttons */
        .login-btn-container {
            display: flex;
            flex-direction: column;
            align-items: center; /* Ensures buttons are centered */
            gap: 10px;
        }

        .login-btn-container .btn {
            width: 80%; /* Keeps buttons balanced and centered */
        }

        /* Responsive Design */
        @media (max-width: 600px) {
            .container {
                max-width: 90%;
            }
            .login-btn-container .btn {
                width: 100%; /* Full-width on smaller screens */
            }
        }
    </style>
</head>
<body>
    <script src="assets/script.js"></script>
    <div class="container">
        <h1>📚 Welcome to Smart Parent-Teacher Communication System</h1>

        <!-- Registration Section -->
        <div class="section">
            <h2>📝 New User? Register Here</h2>
            <form id="registerForm">
                <label for="userType">👨‍🏫 I am a:</label>
                <select id="userType" name="userType" required>
                    <option value="">🔽 Select User Type</option>
                    <option value="teacher/register.php">👩‍🏫 Teacher</option>
                    <option value="parent/register.php">👨‍👩‍👦 Parent</option>
                </select>
                <button class="btn" type="button" onclick="redirectToRegister()">✅ Register</button>
            </form>
        </div>

        <!-- Login Section -->
        <div class="section">
            <h2>🔐 Login</h2>
            <div class="login-btn-container">
                <button class="btn" onclick="location.href='teacher/login.php'">📖 Teacher Login</button>
                <button class="btn" onclick="location.href='parent/login.php'">🏡 Parent Login</button>
                <button class="btn" onclick="location.href='admin/login.php'">🛠️ Admin Login</button>
            </div>
        </div>
    </div>

    <script>
        function redirectToRegister() {
            var userType = document.getElementById("userType").value;
            if (userType) {
                window.location.href = userType;
            } else {
                alert("⚠️ Please select a user type to register.");
            }
        }
    </script>
</body>
</html>
