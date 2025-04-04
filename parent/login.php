<?php
session_start();
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT * FROM parents WHERE email = ?");
    $stmt->execute([$email]);
    $parent = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($parent && password_verify($password, $parent["password"])) {
        $_SESSION["parent_id"] = $parent["parent_id"];
        header("Location: dashboard.php");
        exit();
    } else {
        $error_message = "❌ Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>👨‍👩‍👧 Parent Login</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
    <div class="rainy-container"></div>
    <div class="clouds-container">
        <div class="cloud"></div>
        <div class="cloud"></div>
        <div class="cloud"></div>
        <div class="cloud"></div>
        <div class="cloud"></div>
    </div>
    <div class="rain-container" id="rain"></div>
    
    <div class="login-container">
        <h2 class="login-title">👨‍👩‍👧 Parent Login</h2>
        
        <?php if (!empty($error_message)): ?>
            <p class="error-msg"> <?php echo $error_message; ?> </p>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <label for="email" class="form-label">📧 <span class='font-style'>Email:</span></label>
            <input type="email" id="email" name="email" class="form-input" required>

            <label for="password" class="form-label">🔒 <span class='font-style'>Password:</span></label>
            <input type="password" id="password" name="password" class="form-input" required>
            
            <button class="btn login-btn" type="submit">✅ Login</button>
        </form>

        <p class="register-link">🔹 Don't have an account? <a href="register.php">Register here</a></p>

        <div class="logout-container">
            <button class="btn logout-btn" onclick="location.href='../logout.php'">🚪 Back to Dashboard</button>
        </div>
    </div>

    <script>
        function createRain() {
            let rainContainer = document.getElementById("rain");
            for (let i = 0; i < 200; i++) {
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
