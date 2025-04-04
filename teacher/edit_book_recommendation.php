<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION["teacher_id"])) {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION["teacher_id"];
$book_id = $_GET["id"];

$stmt = $conn->prepare("SELECT * FROM book_recommendations WHERE id = ? AND teacher_id = ?");
$stmt->execute([$book_id, $teacher_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    header("Location: teacher_recommendations.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $price = $_POST["price"];
    $description = $_POST["description"];
    $purchase_link = $_POST["purchase_link"];

    $stmt = $conn->prepare("UPDATE book_recommendations SET title = ?, price = ?, description = ?, purchase_link = ? WHERE id = ? AND teacher_id = ?");
    $stmt->execute([$title, $price, $description, $purchase_link, $book_id, $teacher_id]);

    header("Location: teacher_recommendations.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book Recommendation</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f8f9fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 15px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin: 10px 0 5px;
            text-align: left;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        textarea {
            resize: none;
            height: 80px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            margin-top: 15px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease-in-out;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-btn {
            display: inline-block;
            text-decoration: none;
            background-color: #6c757d;
            color: white;
            padding: 10px;
            width: 100%;
            text-align: center;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 16px;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>✏ Edit Book Recommendation</h2>

    <form method="POST">
        <label>Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($book['title']); ?>" required>

        <label>Price:</label>
        <input type="text" name="price" value="<?= htmlspecialchars($book['price']); ?>" required>

        <label>Description:</label>
        <textarea name="description" required><?= htmlspecialchars($book['description']); ?></textarea>

        <label>Purchase Link:</label>
        <input type="text" name="purchase_link" value="<?= htmlspecialchars($book['purchase_link']); ?>">

        <button type="submit">✅ Update Book</button>
    </form>

    <a href="teacher_recommendations.php" class="back-btn">⬅ Back to Recommendations</a>
</div>

</body>
</html>
