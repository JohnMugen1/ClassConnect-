<?php
session_start();
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $conn->prepare("SELECT * FROM teachers WHERE email = ? AND is_approved = 1");
    $stmt->execute([$email]);
    $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($teacher && password_verify($password, $teacher["password"])) {
        $_SESSION["teacher_id"] = $teacher["teacher_id"];
        header("Location: dashboard.php");
        exit();
    } else {
        $error_message = "❌ Invalid credentials or approval pending!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📚 Teacher Login</title>
    <link rel="stylesheet" href="../assets/styles.css">
</head>
<body>
   <div class="rainy-container"></div>

   <!-- ☁️ Moving Clouds -->
   <div class="clouds-container">
      <div class="cloud"></div>
      <div class="cloud"></div>
      <div class="cloud"></div>
      <div class="cloud"></div>
      <div class="cloud"></div>
   </div>

   <!-- 🌧️ Rain Effect -->
   <div class="rain-container" id="rain"></div>
   <div class="login-container">
        <h2 class="login-title">👨‍🏫 Teacher Login</h2>
        
        <!-- Error Message -->
        <?php if (!empty($error_message)): ?>
            <p class="error-msg"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <label for="email" class="form-label">📧 <span class='font-style'>Email:</span></label>
            <input type="email" id="email" name="email" class="form-input" required>

            <label for="password" class="form-label">🔒 <span class='font-style'>Password:</span></label>
            <input type="password" id="password" name="password" class="form-input" required>

            <button class="btn login-btn" type="submit">✅ Login</button>
        </form>

        <p class="register-link">🔹 Don't have an account? <a href="register.php">Register here</a></p>

        <!-- 🚪 Logout Button -->
        
        <div class="logout-container">
               <button class="btn logout-btn" onclick="location.href='../logout.php'">🚪 Back to Dashboard</button>
        </div>
    </div>
    <script>
        // 🌧️ Generate Continuous Rain
        function createRain() {
            let rainContainer = document.getElementById("rain");
            for (let i = 0; i < 200; i++) {  // Generate 200 raindrops
                let drop = document.createElement("div");
                drop.classList.add("raindrop");
                drop.style.left = Math.random() * 100 + "vw";
                drop.style.animationDuration = Math.random() * 1.5 + 0.5 + "s"; // Random speed
                drop.style.animationDelay = Math.random() * 2 + "s"; // Random delay
                rainContainer.appendChild(drop);
            }
        }

        createRain();
    </script>
</body>
</html>
