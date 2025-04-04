<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION["teacher_id"])) {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION["teacher_id"];

// Fetch all books recommended by this teacher
$stmt = $conn->prepare("SELECT * FROM book_recommendations WHERE teacher_id = ?");
$stmt->execute([$teacher_id]);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>📚 Book Recommendations</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 0;
            text-align: center;
            position: relative;
        }

        h2 {
            margin-top: 20px;
            color: #2c3e50;
        }

        /* Logout Button */
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #d32f2f;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #b71c1c;
        }

        a.add-btn {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin: 20px 0;
        }

        a.add-btn:hover {
            background: #218838;
        }

        .book-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            max-width: 1000px;
            margin: 20px auto;
            padding: 0 20px;
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
            width: 100px;
            height: 140px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .book-card h3 {
            font-size: 16px;
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
        }

        .buy-btn {
            background: #007bff;
            color: white;
        }

        .buy-btn:hover {
            background: #0056b3;
        }

        .edit-btn {
            background: #ffc107;
            color: #333;
        }

        .edit-btn:hover {
            background: #e0a800;
        }

        .delete-btn {
            background: #dc3545;
            color: white;
        }

        .delete-btn:hover {
            background: #c82333;
        }

        @media screen and (max-width: 768px) {
            .logout-btn {
                position: relative;
                display: block;
                width: auto;
                margin: 10px auto;
            }
        }
    </style>
</head>
<body>

<!-- Logout Button -->
<a href="dashboard.php" class="logout-btn">🚪 << Back to dashboard</a>

<h2>📚 Book Recommendations</h2>
<a href="add_book_recommendation.php" class="add-btn">➕ Add New Recommendation</a>

<div class="book-list">
    <?php if (empty($books)): ?>
        <p>No book recommendations available.</p>
    <?php else: ?>
        <?php foreach ($books as $book): ?>
            <div class="book-card">
                <img src="../uploads/<?= htmlspecialchars($book['book_image']); ?>" alt="Book Cover">
                <h3><?= htmlspecialchars($book['title']); ?></h3>
                <p>💰 Price: <?= htmlspecialchars($book['price']); ?></p>
                <p><?= htmlspecialchars($book['description']); ?></p>
                <a href="<?= htmlspecialchars($book['purchase_link']); ?>" target="_blank" class="buy-btn">🔗 Buy Now</a>
                <a href="edit_book_recommendation.php?id=<?= $book['id']; ?>" class="edit-btn">✏ Edit</a>
                <a href="delete_book_recommendation.php?id=<?= $book['id']; ?>" onclick="return confirm('Are you sure?')" class="delete-btn">🗑 Delete</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
