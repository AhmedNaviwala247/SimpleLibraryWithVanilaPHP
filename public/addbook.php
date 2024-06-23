<?php
session_start();
if (!$_SESSION['logged_in']) {
    header('location: signin.php');
    exit;
}

if (!$_SESSION['is_admin']) {
    header('location: youarenotadmin.php');
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Library - Add Book</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $title = $_POST["title"];
        $author = $_POST["author"];
        $genre = $_POST["genre"];
        $bookcover = $_POST["bookcover"];
        $description = $_POST["description"];


        $stmt = $db->prepare("INSERT INTO books(title, author, genre, bookcover, description)
                            VALUE(?,?,?,?,?)");

        $stmt->bind_param("ssss", $title, $author, $genre, $bookcover, $description);

        if ($stmt->execute()) {
            header('location: index.php');
            exit;
        } else {
            echo "Something went wrong";
            return false;
        }

    } catch (\Throwable $e) {
        echo $e;
    }
}


?>

<body>
    <div class="container-w400">
        <h1>Add Book</h1>
        <form action="" method="POST">
            <input type="text" name="title" placeholder="Name" required><br>
            <input type="text" name="author" placeholder="Author" required><br>
            <input type="text" name="genre" placeholder="Genre" required><br>
            <input type="text" name="description" placeholder="description" required><br>
            <input type="text" name="bookcover" placeholder="Book Cover Image Link" required><br>
            <button type="submit">Add Book</button>
        </form>
    </div>
</body>

</html>