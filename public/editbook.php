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
    <link rel="stylesheet" href="styles/styles.css">
    <title>Book Library - Edit Book</title>
</head>
<?php

include "db.php";
$error = '';
$noBook = false;

if (isset($_GET["bookId"])) {
    $bookId = $_GET["bookId"];

    try {
        $query = "SELECT * FROM books WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('i', $bookId);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $books = $result->fetch_all(MYSQLI_ASSOC);
        } else {
            $error = 'No Book Found To Edit';
        }
        if (empty($books)) {
            $noBook = true;
        }
        foreach ($books as $book) {
        }
    } catch (\Exception $e) {
        $error = $e;
        return false;
    }
} else {
    $noBook = true;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($books)) {

    try {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $genre = $_POST['genre'];
        $description = $_POST['description'];
        $bookcover = $_POST['bookcover'];

        $query = "UPDATE books SET title = ?, author = ?, genre = ?, bookcover = ?, description = ? WHERE id = $bookId";
        $stmt = $db->prepare($query);
        $stmt->bind_param("sssss", $title, $author, $genre, $bookcover, $description);
        if ($stmt->execute()) {
            header('location: index.php');
            exit;
        } else {
            $error = "something went wrong";
        }

    } catch (\Exception $e) {
        $error = $e;
    }
}

?>

<body>
    <?php if ($noBook) { ?>
        <div class="container-center">
            <h1>No Book Found, kindly go Back</h1>
            <a href="index.php" class="button">Go to Homepage</a>
        </div>
    <?php } ?>
    <div class="container-w400">
        <h1>Edit Book </h1>
        <?php if (!empty($error)) { ?>
            <div class="error-message">
                <?php echo $error; ?>
            </div>
        <?php } ?>
        <form action="" method="POST">
            <input type="text" name="title" placeholder="Name" value="<?php echo $book['title'] ?>" required><br>
            <input type="text" name="author" placeholder="Author" value="<?php echo $book['author'] ?>" required><br>
            <input type="text" name="genre" placeholder="Genre" value="<?php echo $book['genre'] ?>" required><br>
            <input type="text" name="description" placeholder="Description" value="<?php echo $book['description'] ?>"
                required><br>
            <input type="text" name="bookcover" placeholder="Book Cover Image Link"
                value="<?php echo $book['bookcover'] ?>" required><br>
            <button type="submit">Edit Book</button>
        </form>
    </div>
</body>

</html>