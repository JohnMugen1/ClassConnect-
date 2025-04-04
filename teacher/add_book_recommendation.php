<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION["teacher_id"])) {
    header("Location: login.php");
    exit();
}

$teacher_id = $_SESSION["teacher_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = htmlspecialchars($_POST["title"]);
    $price = htmlspecialchars($_POST["price"]);
    $description = htmlspecialchars($_POST["description"]);
    $purchase_link = filter_var($_POST["purchase_link"], FILTER_SANITIZE_URL);

    // Handle Image Upload Securely
    if (isset($_FILES["book_image"]) && $_FILES["book_image"]["error"] === 0) {
        $allowed_types = ["image/jpeg", "image/png", "image/jpg"];
        $file_type = mime_content_type($_FILES["book_image"]["tmp_name"]);

        if (!in_array($file_type, $allowed_types)) {
            die("Invalid file type! Only JPG and PNG are allowed.");
        }

        $file_ext = pathinfo($_FILES["book_image"]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid("book_", true) . "." . $file_ext;
        $upload_path = "../uploads/" . $new_filename;

        if (!move_uploaded_file($_FILES["book_image"]["tmp_name"], $upload_path)) {
            die("Failed to upload file.");
        }
    } else {
        die("No file uploaded or an error occurred.");
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO book_recommendations (teacher_id, book_image, title, price, description, purchase_link) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$teacher_id, $new_filename, $title, $price, $description, $purchase_link]);

    header("Location: teacher_recommendations.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book Recommendation</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f9;
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
            background-color: #28a745;
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
            background-color: #218838;
        }

        .back-btn {
            display: inline-block;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            padding: 10px;
            width: 100%;
            text-align: center;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 16px;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📚 Add a Book Recommendation</h2>

    <form method="POST" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="title" required>

        <label>Price:</label>
        <input type="text" name="price" required>

        <label>Description:</label>
        <textarea name="description" required></textarea>

        <label>Purchase Link:</label>
        <input type="text" name="purchase_link" required>

        <label>Book Image (JPG/PNG only):</label>
        <input type="file" name="book_image" accept=".jpg,.jpeg,.png" required>

        <button type="submit">Submit 📤</button>
    </form>

    <a href="teacher_recommendations.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

</body>
</html>
