<?php
// Start session securely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    session_regenerate_id(true); // Prevents session fixation
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
    echo "Error: Parent data not found!";
    exit();
}

$student_id = $parent["student_id"];
$student_name = $parent["student_name"];

// Fetch unread notifications count
$notification_stmt = $conn->prepare("SELECT COUNT(*) AS unread_count FROM notifications WHERE parent_id = ? AND is_read = 0");
$notification_stmt->execute([$parent_id]);
$notification_count = $notification_stmt->fetch(PDO::FETCH_ASSOC)['unread_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: #f4f4f4;
        }
        .header {
            background: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
        }
        .container {
            display: flex;
            flex: 1;
        }
        .sidebar {
            width: 250px;
            background: #343a40;
            padding: 20px;
            color: white;
            transition: 0.3s ease;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
            margin: 5px 0;
            border-radius: 5px;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .sidebar .notification-badge {
            background: red;
            color: white;
            padding: 5px 10px;
            border-radius: 50%;
            font-size: 0.8rem;
            margin-left: 5px;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px;
            background-color:#000;
        }
        .dashboard-info {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 95%;
            background: linear-gradient(green, orange);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .motivation-box {
            width: 80%;
            max-width: 600px;
            padding: 40px;
            background: linear-gradient(orange,black);
            border: 5px solidrgb(0, 255, 55);
            border-radius: 10px;
            text-align: center;
            font-size: 1.4rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: bold;
            color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: opacity 0.5s ease-in-out;
        }
        .footer {
            background: #007bff;
            color: white;
            text-align: center;
            padding: 10px;
            margin-top: auto;
        }
        .menu-toggle {
            display: none;
            font-size: 1.5rem;
            background: none;
            border: none;
            color: white;
            padding: 10px;
            cursor: pointer;
        }
        .welcome-text, .footer {
    font-size: 1.5rem;
    font-weight: bold;
    text-transform: uppercase;
    display: inline-block;
    padding: 5px 15px;
    border-radius: 5px;
    background-color:#000;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(255, 69, 0, 0.8), 
                0 0 30px rgba(255, 140, 0, 0.7), 
                0 0 45px rgba(255, 0, 0, 0.6); /* Fire effect */
    animation: flicker 1.5s infinite alternate;
}

/* Flickering fire effect */
@keyframes flicker {
    0% { box-shadow: 0 0 15px rgba(255, 69, 0, 0.8), 0 0 30px rgba(255, 140, 0, 0.7), 0 0 45px rgba(255, 0, 0, 0.6); }
    50% { box-shadow: 0 0 25px rgba(255, 99, 71, 0.9), 0 0 40px rgba(255, 165, 0, 0.8), 0 0 55px rgba(255, 0, 0, 0.7); }
    100% { box-shadow: 0 0 20px rgba(255, 69, 0, 0.7), 0 0 35px rgba(255, 140, 0, 0.6), 0 0 50px rgba(255, 0, 0, 0.5); }
}

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
                padding: 0;
                position: absolute;
                height: 100%;
                transition: 0.3s ease;
            }
            .sidebar.active {
                width: 250px;
                padding: 20px;
            }
            .menu-toggle {
                display: block;
                position: absolute;
                top: 10px;
                left: 10px;
            }
            .main-content {
                margin: 20px 10px;
            }
        }
        .welcome-msg {
            margin:0.4rem;
            font-family: serif;
            font-size:1.8rem;
        }
        #special-love {
            font-style: italic;
            font-weight: bold;
            color: yellow;
        }
        .logout-btn {
    display: block;
    text-align: center;
    background: linear-gradient(to right, #ff416c, #ff4b2b);
    color: white;
    font-size: 1rem;
    font-weight: bold;
    padding: 12px 20px;
    border-radius: 25px;
    transition: all 0.3s ease-in-out;
    text-decoration: none;
    margin-top: 15px;
}

.logout-btn:hover {
    background: linear-gradient(to right, #ff4b2b, #ff416c);
    transform: scale(1.05);
}

    </style>
</head>
<body>

    <header class="header">
        <button class="menu-toggle" onclick="toggleSidebar()">☰</button>
        <span class="welcome-text">
            <?php
            $welcomeText = "WELCOME, " . strtoupper($parent["parent_name"]);
            $colors = ["yellow", "lime", "white"];

            $letters = str_split($welcomeText);
            foreach ($letters as $index => $letter) {
                $color = $colors[$index % count($colors)];
                echo "<span style='color: $color;'>$letter</span>";
            }
            ?>
        </span>
        </br>
        <p class='welcome-msg'><strong><?= htmlspecialchars($student_name) ?></strong> <span id='special-love'>Is Destined For Greatness!</span></p>
    </header>

    <div class="container">
        <nav class="sidebar" id="sidebar">
            <a href="../feedback/view_feedback.php">📢 Notifications 
                <?php if ($notification_count > 0): ?>
                    <span class="notification-badge"><?= htmlspecialchars($notification_count) ?></span>
                <?php endif; ?>
            </a>
            <a href="submit_feedback.php">📝 Submit Feedback</a>
            <a href="view_feedback.php">📄 View Feedback</a>
            <a href="view_recommendations.php">🔍 View Recommendations</a>
            <a href="view_report.php">📖 View Performance Report</a>
            <a href="../index.php" class="logout-btn">🚪 Logout</a>
        </nav>

        <main class="main-content">
            <div class="dashboard-info">
                <div class="motivation-box">
                    <p id="quote-text">Loading...</p>
                </div>
            </div>
        </main>
    </div>

    <footer class="footer">
        &copy; <?= date("Y") ?> Smart Parent-Teacher Communication System
    </footer>

    <script>
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("active");
        }

        const quotes = [
    "A child's mind is not a vessel to be filled but a fire to be kindled.",
    "The best way to guide your child is to lead by example.",
    "Believe in your child, and they will believe in themselves.",
    "Every child is a different kind of flower, and all together, they make this world a beautiful garden.",
    "Your child will follow your example, not your advice.",
    "Children learn more from what you are than what you teach.",
    "The greatest legacy we can leave our children is happy memories.",
    "The way we talk to our children becomes their inner voice.",
    "Encourage your child to dream big and never give up.",
    "Parenting is not about being perfect, but about being present.",
    "A parent's love is whole no matter how many times it is divided.",
    "Guide your child, but don’t control them—help them find their own path.",
    "Little eyes are watching, little ears are listening, little hands are learning from you every day.",
    "Patience and love will nurture a child's confidence and growth.",
    "Speak to your children as if they are the wisest, kindest, most beautiful and magical humans on earth.",
    "Children are great imitators, so give them something great to imitate.",
    "Be the person you want your child to become.",
    "Hug your children at every opportunity. They will remember your love more than your words.",
    "Show your child love, even when they test your patience.",
    "The best inheritance a parent can give their child is a few minutes of their time every day.",
    "Listening to your child today will help them listen to you tomorrow.",
    "What you do has a far greater impact than what you say.",
    "Discipline is not about punishment; it’s about teaching and guiding.",
    "Every moment with your child is a chance to build a lifetime of memories.",
    "The best gift you can give your child is the gift of your presence.",
    "Strong roots make strong trees. Give your child the foundation they need to grow.",
    "Tell your child you love them every day—words matter.",
    "A child's potential is limited only by the opportunities they are given.",
    "Encouragement is the fuel that helps children reach their full potential.",
    "Children thrive when they know they are loved, safe, and supported.",
    "Love, patience, and understanding are the best tools in parenting.",
    "No one is born a perfect parent. We all learn as we go.",
    "Respect your child, and they will learn to respect others.",
    "Raising a child takes a village—never be afraid to ask for support.",
    "The best teachers are those who show you where to look, but don’t tell you what to see.",
    "A child’s future is shaped by the love, guidance, and encouragement they receive today.",
    "Your child's heart is a treasure—protect it with kindness and care.",
    "Teach your child that mistakes are just lessons in disguise.",
    "Quality time is the best time—make every moment count.",
    "Children are like mirrors; they reflect back what they see in their parents.",
    "Never underestimate the power of a parent's words.",
    "Your encouragement today will build their confidence for a lifetime.",
    "Praise your child for effort, not just results.",
    "Nurture their curiosity—it will take them far in life.",
    "A safe and loving home is the best environment for a child's growth.",
    "Encourage your child to ask questions and seek knowledge.",
    "A child who feels loved will always believe they are worthy.",
    "Teach kindness by being kind, teach respect by showing respect.",
    "A child's self-worth begins with knowing they are loved just as they are.",
    "Parenting is about leading with love, not fear.",
    "When a child knows they are loved, they grow into confident adults.",
    "Your presence is more important than any present you can buy.",
    "Teaching values is more important than teaching rules.",
    "Your child may forget what you said, but they will never forget how you made them feel.",
    "Build a relationship with your child based on trust, not fear.",
    "Guide them with love, teach them with patience, and watch them grow with confidence.",
    "Your child’s success starts with belief—yours and theirs.",
    "A child's mind is like a sponge—fill it with positivity and wisdom.",
    "Children may not always listen to you, but they will always learn from your actions.",
    "The way we treat our children today shapes the adults they will become tomorrow.",
    "Model the values you want to see in your child.",
    "Happiness is watching your child grow into a kind, caring, and confident individual.",
    "Your patience today will create a resilient and understanding adult tomorrow.",
    "Teach them that kindness and courage will take them further than anything else.",
    "Small moments of love and attention add up to a lifetime of security.",
    "Encourage them to be their best, not to be perfect.",
    "Give them wings to fly and roots to come back to.",
    "The love and lessons you give your child today will echo through generations.",
    "Your love and support are the most powerful tools for your child's success.",
    "Show them how to love by loving them unconditionally.",
    "Every child is born with potential—help them discover it.",
    "A parent's love is the most powerful force in shaping a child’s future.",
    "Listen when your child speaks, and they will listen when you teach.",
    "Children grow best when they are watered with love and encouragement.",
    "The best way to discipline a child is to set a good example.",
    "Every child needs a champion—be that champion for your child.",
    "Teaching a child respect starts with showing them respect.",
    "Children do not need perfect parents; they need loving and supportive ones.",
    "Confidence comes from knowing you are loved, capable, and supported.",
    "Every child deserves to feel safe, loved, and valued.",
    "Parenting is about progress, not perfection.",
    "Celebrate small victories, both yours and your child's.",
    "A good parent prepares their child for the road, not the road for their child.",
    "Give your child the tools to build a strong future.",
    "Encourage curiosity—it’s the key to lifelong learning.",
    "No act of love toward your child is ever wasted.",
    "Your child is watching—show them kindness, patience, and resilience.",
    "Teach them that hard work and kindness always pay off.",
    "Your child’s self-esteem is built by the words you speak to them.",
    "A child who is listened to will learn to listen to others.",
    "Trust and respect are the foundation of a strong parent-child relationship.",
    "Parenting is about teaching your child to make good choices, not just following rules.",
    "Let them make mistakes—learning comes from trying and failing.",
    "Teach them that their voice matters, and they will grow into confident individuals.",
    "Encourage them to believe in themselves, and they will achieve great things.",
    "The time you invest in your child will shape their future.",
    "Love and support create the foundation for a happy, confident child.",
    "Help your child discover their strengths and passions.",
    "When you teach your child gratitude, you teach them happiness.",
    "Guide your child with love, and they will always find their way home.",
    "Teaching responsibility today creates responsible adults tomorrow.",
    "Parenting is the art of balancing guidance with freedom.",
    "A child’s potential is limitless when they feel loved and encouraged.",
    "Parenting is the most challenging and rewarding job you’ll ever have.",
    "Your child's happiness begins with knowing they are truly loved.",
    "Encourage, uplift, and believe in your child every day."
];


const bibleVerses = [
    "Proverbs 22:6 - 'Train up a child in the way he should go, and when he is old, he will not depart from it.'",
    "Philippians 4:13 - 'I can do all things through Christ who strengthens me.'",
    "Ephesians 6:4 - 'Fathers, do not provoke your children to anger, but bring them up in the discipline and instruction of the Lord.'",
    "Colossians 3:21 - 'Fathers, do not embitter your children, or they will become discouraged.'",
    "Deuteronomy 6:6-7 - 'These commandments that I give you today are to be on your hearts. Impress them on your children. Talk about them when you sit at home and when you walk along the road, when you lie down and when you get up.'",
    "Psalm 127:3 - 'Children are a heritage from the Lord, offspring a reward from him.'",
    "Isaiah 54:13 - 'All your children shall be taught by the Lord, and great shall be the peace of your children.'",
    "3 John 1:4 - 'I have no greater joy than to hear that my children are walking in the truth.'",
    "Proverbs 29:17 - 'Discipline your children, and they will give you peace; they will bring you the delights you desire.'",
    "Psalm 139:13-14 - 'For you created my inmost being; you knit me together in my mother’s womb. I praise you because I am fearfully and wonderfully made.'",
    "James 1:5 - 'If any of you lacks wisdom, let him ask God, who gives generously to all without reproach, and it will be given to him.'",
    "Matthew 19:14 - 'Jesus said, “Let the little children come to me, and do not hinder them, for the kingdom of heaven belongs to such as these.”'",
    "Proverbs 1:8-9 - 'Listen, my son, to your father’s instruction and do not forsake your mother’s teaching. They are a garland to grace your head and a chain to adorn your neck.'",
    "Joshua 1:9 - 'Be strong and courageous. Do not be afraid; do not be discouraged, for the Lord your God will be with you wherever you go.'",
    "Psalm 34:11 - 'Come, my children, listen to me; I will teach you the fear of the Lord.'",
    "2 Timothy 3:16-17 - 'All Scripture is God-breathed and is useful for teaching, rebuking, correcting and training in righteousness, so that the servant of God may be thoroughly equipped for every good work.'",
    "Matthew 5:16 - 'Let your light shine before others, that they may see your good deeds and glorify your Father in heaven.'",
    "Psalm 78:4 - 'We will not hide them from their descendants; we will tell the next generation the praiseworthy deeds of the Lord, his power, and the wonders he has done.'",
    "Deuteronomy 5:16 - 'Honor your father and your mother, as the Lord your God has commanded you, so that you may live long and that it may go well with you in the land the Lord your God is giving you.'",
    "Luke 2:52 - 'And Jesus grew in wisdom and stature, and in favor with God and man.'",
    "Hebrews 12:11 - 'No discipline seems pleasant at the time, but painful. Later on, however, it produces a harvest of righteousness and peace for those who have been trained by it.'",
    "Psalm 37:25 - 'I was young and now I am old, yet I have never seen the righteous forsaken or their children begging bread.'",
    "John 14:27 - 'Peace I leave with you; my peace I give you. I do not give to you as the world gives. Do not let your hearts be troubled and do not be afraid.'",
    "Proverbs 14:26 - 'Whoever fears the Lord has a secure fortress, and for their children it will be a refuge.'",
    "Romans 8:28 - 'And we know that in all things God works for the good of those who love him, who have been called according to his purpose.'",
    "Proverbs 20:7 - 'The righteous lead blameless lives; blessed are their children after them.'",
    "Jeremiah 29:11 - 'For I know the plans I have for you, declares the Lord, plans to prosper you and not to harm you, plans to give you hope and a future.'",
    "Isaiah 40:31 - 'But those who hope in the Lord will renew their strength. They will soar on wings like eagles; they will run and not grow weary, they will walk and not be faint.'",
    "1 Corinthians 16:14 - 'Do everything in love.'",
    "Psalm 23:1 - 'The Lord is my shepherd; I shall not want.'",
    "Philippians 1:6 - 'Being confident of this, that he who began a good work in you will carry it on to completion until the day of Christ Jesus.'",
    "Isaiah 41:10 - 'So do not fear, for I am with you; do not be dismayed, for I am your God. I will strengthen you and help you; I will uphold you with my righteous right hand.'",
    "Galatians 6:9 - 'Let us not become weary in doing good, for at the proper time we will reap a harvest if we do not give up.'",
    "James 1:19 - 'Everyone should be quick to listen, slow to speak and slow to become angry.'",
    "Psalm 91:11 - 'For he will command his angels concerning you to guard you in all your ways.'",
    "Romans 12:12 - 'Be joyful in hope, patient in affliction, faithful in prayer.'",
    "2 Corinthians 5:7 - 'For we live by faith, not by sight.'",
    "Proverbs 17:6 - 'Children’s children are a crown to the aged, and parents are the pride of their children.'",
    "Colossians 3:23 - 'Whatever you do, work at it with all your heart, as working for the Lord, not for human masters.'",
    "Matthew 6:34 - 'Therefore do not worry about tomorrow, for tomorrow will worry about itself. Each day has enough trouble of its own.'",
    "1 Thessalonians 5:16-18 - 'Rejoice always, pray continually, give thanks in all circumstances; for this is God’s will for you in Christ Jesus.'",
    "Psalm 46:1 - 'God is our refuge and strength, an ever-present help in trouble.'",
    "Mark 9:37 - 'Whoever welcomes one of these little children in my name welcomes me; and whoever welcomes me does not welcome me but the one who sent me.'",
    "Romans 15:13 - 'May the God of hope fill you with all joy and peace as you trust in him, so that you may overflow with hope by the power of the Holy Spirit.'",
    "Psalm 121:8 - 'The Lord will watch over your coming and going both now and forevermore.'",
    "1 Peter 5:7 - 'Cast all your anxiety on him because he cares for you.'"
];


        let index = 0;
        function changeQuote() {
            const quoteBox = document.getElementById("quote-text");
            quoteBox.style.opacity = 0;
            setTimeout(() => {
                quoteBox.textContent = (index % 2 === 0) ? quotes[Math.floor(index / 2) % quotes.length] : bibleVerses[Math.floor(index / 2) % bibleVerses.length];
                quoteBox.style.opacity = 1;
                index++;
            }, 500);
        }

        setInterval(changeQuote, 8000);
        changeQuote();
    </script>

</body>
</html>
