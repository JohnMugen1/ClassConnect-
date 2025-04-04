<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION["parent_id"])) {
    header("Location: login.php");
    exit();
}

// Fetch all book recommendations from teachers
$stmt = $conn->prepare("SELECT br.*, t.full_name AS teacher_name FROM book_recommendations br 
                        JOIN teachers t ON br.teacher_id = t.teacher_id 
                        ORDER BY br.id DESC");
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>📚 Recommended Books</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 20px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        /* Logout Button */
        .logout-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            background: red;
            color: white;
            padding: 8px 15px;
            font-size: 14px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: darkred;
        }

        /* Book List Grid */
        .book-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            max-width: 1000px;
            margin: auto;
            padding: 10px;
        }

        .book-card {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.2s ease-in-out;
        }

        .book-card:hover {
            transform: scale(1.03);
        }

        .book-card img {
            width: 150px;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .book-card h3 {
            font-size: 18px;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .book-card p {
            font-size: 14px;
            color: #555;
            margin: 5px 0;
        }

        .book-card a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 14px;
            text-decoration: none;
            font-weight: bold;
            background: #007bff;
            color: white;
        }

        .book-card a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<a href="dashboard.php" class="logout-btn">🔴 << Back</a>

<h2>📚 Recommended Books for Your Child</h2>

<div class="book-list">
    <?php if (empty($books)): ?>
        <p>No book recommendations available.</p>
    <?php else: ?>
        <?php foreach ($books as $book): ?>
            <div class="book-card">
                <img src="../uploads/<?= htmlspecialchars($book['book_image']); ?>" alt="Book Cover">
                <h3><?= htmlspecialchars($book['title']); ?></h3>
                <p><strong>👨‍🏫 Recommended by:</strong> <?= htmlspecialchars($book['teacher_name']); ?></p>
                <p>💰 <strong>Price:</strong> <?= htmlspecialchars($book['price']); ?></p>
                <p>📖 <strong>Description:</strong> <?= htmlspecialchars($book['description']); ?></p>
                <a href="<?= htmlspecialchars($book['purchase_link']); ?>" target="_blank">🛒 Buy Now</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
