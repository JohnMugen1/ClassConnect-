<?php
session_start();
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = :username");
    $stmt->execute(["username" => $username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin["password"])) {
        $_SESSION["admin_id"] = $admin["admin_id"];
        header("Location: dashboard.php");
        exit();
    } else {
        $error_message = "❌ Invalid login credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🛠️ Admin Login</title>
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
        <h2 class="login-title">🛠️ Admin Login</h2>

        <?php if (!empty($error_message)): ?>
            <p class="error-msg"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <label for="username" class="form-label">👤 <span class="font-style">Username:</span></label>
            <input type="text" id="username" name="username" class="form-input" required>

            <label for="password" class="form-label">🔒 <span class="font-style">Password:</span></label>
            <input type="password" id="password" name="password" class="form-input" required>

            <button class="btn login-btn" type="submit">✅ Login</button>
        </form>

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
