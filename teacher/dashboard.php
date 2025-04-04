<?php
session_start();
if (!isset($_SESSION["teacher_id"])) {
    header("Location: login.php");
    exit();
}

require_once "../config/database.php";
$teacher_id = $_SESSION["teacher_id"];
$stmt = $conn->prepare("SELECT full_name FROM teachers WHERE teacher_id = :teacher_id");
$stmt->execute(["teacher_id" => $teacher_id]);
$teacher = $stmt->fetch(PDO::FETCH_ASSOC);
$teacher_name = $teacher ? htmlspecialchars($teacher["full_name"]) : "Teacher";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; scroll-behavior: smooth; }
        body { color: white; }

        /* Fixed Header */
        .header { 
            background: #2C3E50; color: white; padding: 15px 30px; 
            display: flex; justify-content: space-between; align-items: center; 
            position: fixed; top: 0; width: 100%; z-index: 1000; 
        }
        /* Multi-colored logo with fire shadow */
      .logo {
          font-size: 2rem;
          font-weight: bold;
          text-transform: uppercase;
          background: linear-gradient(45deg, #FFD700,rgb(255, 251, 0), #FF0000);
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
          font-family: 'Courier New', monospace;
          letter-spacing: 2px;
          text-shadow: 0 0 5px #FF4500, 0 0 10px #FF6347, 0 0 20px #FF0000;
        }

        .menu-toggle { 
            display: none; 
            font-size: 1.8rem; 
            cursor: pointer; 
        }

        .nav-links { display: flex; 
            gap: 20px; 
        }
        .nav-links a { color: white; text-decoration: none; font-weight: bold; transition: 0.3s; }
        .nav-links a:hover { color: #FFD700; }

        .footer-link { color: #FFD700; text-decoration: none; font-weight: bold; }
        .footer-link:hover { text-decoration: underline; }

        .logout-btn { 
            background: #E74C3C; padding: 10px 15px; border-radius: 5px; 
            text-decoration: none; color: white; font-weight: bold; 
            transition: 0.3s; 
        }
        .logout-btn:hover { background: #C0392B; }

        /* Full-Page Sections with Different Colors */
        .dashboard-section {
            height: 100vh;
            display: flex; justify-content: center; align-items: center;
            text-align: center; padding: 20px;
            scroll-margin-top: 90px;
        }
        #students { background: #3498DB; } /* Blue */
        #feedback { background: #E67E22; } /* Orange */
        #performance { background: #27AE60; } /* Green */
        #notifications { background: #8E44AD; } /* Purple */
        #recommendations { background: #C0392B; } /* Red */

        .dashboard-grid { max-width: 600px; width: 100%; }
        .dashboard-card { 
            background: white; padding: 25px; border-radius: 8px; 
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); 
            transition: 0.3s; text-align: center; color: black;
        }
        .dashboard-card:hover { transform: translateY(-5px); box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15); }
        .dashboard-card h3 { margin-bottom: 10px; font-size: 1.8rem; color: #2C3E50; }

        /* Styled Descriptions */
        .dashboard-card p {
            font-size: 1.1rem;
            color: #444;
            font-style: italic;
            line-height: 1.6;
        }

        .card-btn { 
            display: inline-block; padding: 12px 16px; color: white; font-weight: bold; 
            border-radius: 6px; text-decoration: none; background: #2C3E50; 
            margin-top: 10px; transition: 0.3s; font-size: 1.2rem;
        }
        .card-btn:hover { background: #FFD700; color: black; }

        /* Footer */
        .footer { background: #2C3E50; color: white; text-align: center; padding: 15px; }
        .footer a { color: #FFD700; text-decoration: none; font-weight: bold; margin: 0 10px; }
        .footer a:hover { text-decoration: underline; }

        /* Responsive */
        @media screen and (max-width: 768px) {
            .header { flex-direction: column; text-align: center; padding: 10px; }
            .menu-toggle { display: block; }
            .nav-links { 
                display: none; flex-direction: column; gap: 10px; 
                width: 100%; text-align: center; padding: 10px; 
                background: #2C3E50; position: absolute; top: 60px; left: 0; 
            }
            .nav-links a { padding: 10px; display: block; }
            .nav-links.active { display: flex; }
        }
    </style>
</head>
<body>

<!-- Fixed Header -->
<header class="header">
    <div class="logo">Teacher Dashboard</div>
    <div class="menu-toggle" onclick="toggleMenu()">☰</div>
    <nav class="nav-links">
        <a href="#students">Students</a>
        <a href="#feedback">Feedback</a>
        <a href="#performance">Performance</a>
        <a href="#notifications">Notifications</a>
        <a href="#recommendations">Recommendations</a>
        <a href="#footer" class="footer-link">Footer</a>
    </nav>
    <a href="../logout.php" class="logout-btn">Logout</a>
</header>

<!-- Main Content -->
<section id="students" class="dashboard-section">
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3>👨‍🎓 Students</h3>
            <p>Register and manage student records. View detailed student profiles and update their information.</p>
            <a href="view_students.php" class="card-btn">Manage Students</a>
        </div>
    </div>
</section>

<section id="feedback" class="dashboard-section">
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3>📝 Feedback</h3>
            <p>Provide feedback to parents about their child's progress, behavior, and strengths.</p>
            <a href="../feedback/submit_feedback.php" class="card-btn">Submit Feedback</a>
        </div>
    </div>
</section>

<section id="performance" class="dashboard-section">
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3>📊 Performance</h3>
            <p>Record and analyze student academic performance across subjects.</p>
            <a href="update_performance.php" class="card-btn">Update Performance</a>
        </div>
    </div>
</section>

<section id="notifications" class="dashboard-section">
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3>🔔 Notifications</h3>
            <p>Notify parent on urgent messages.</p>
            <a href="../notifications/notify_parents.php" class="card-btn">Notify Parent</a>
        </div>
    </div>
</section>

<section id="recommendations" class="dashboard-section">
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3>💡 Recommendations</h3>
            <p>Set personalized Books recommendations to improve student learning.</p>
            <a href="teacher_recommendations.php" class="card-btn">Books Recommendations</a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer id="footer" class="footer">
    <p>&copy; <?= date("Y") ?> Smart Parent-Teacher System. All rights reserved.</p>
</footer>

<script>
    function toggleMenu() {
        let menu = document.querySelector(".nav-links");
        let menuIcon = document.querySelector(".menu-toggle");

        menu.classList.toggle("active");

        menuIcon.innerHTML = menu.classList.contains("active") ? "✖" : "☰";
    }

    document.querySelectorAll(".nav-links a").forEach(link => {
        link.addEventListener("click", () => {
            document.querySelector(".nav-links").classList.remove("active");
            document.querySelector(".menu-toggle").innerHTML = "☰";
        });
    });
</script>

</body>
</html>
