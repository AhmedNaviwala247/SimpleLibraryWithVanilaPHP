<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
    <title>Book Details
    </title>
</head>

<?php
include "db.php";
$reviews = array();
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

            $queryr = "SELECT * FROM reviews WHERE book_id = ?";
            $stmtr = $db->prepare($queryr);
            $stmtr->bind_param("i", $bookId);
            $stmtr->execute();
            $result_reviews = $stmtr->get_result();

            if ($result_reviews->num_rows > 0) {
                while ($row = $result_reviews->fetch_assoc()) {
                    $reviews[] = $row;
                }
            }
            $stmtr->close();
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
    }
} else {
    $noBook = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($books)) {
    $user = $_COOKIE['email'];
    $reviewOfUser = $_POST["review"];

    $querypr = "INSERT INTO reviews (book_id, user, review) 
    VALUES (?, ?, ?)";
    $stmtpr = $db->prepare($querypr);
    $stmtpr->bind_param("iss", $bookId, $user, $reviewOfUser);

    if ($stmtpr->execute()) {
        header("Location: book.php?bookId=$bookId");
        exit();
    } else {
        echo "Error: " . $stmtpr->error;
    }

    $stmtpr->close();

}
?>

<body>
    <?php if ($noBook) { ?>
        <div class="container-center">
            <h1>No Book Found, kindly go Back</h1>
            <a href="index.php" class="button">Go to Homepage</a>
        </div>
    <?php } ?>
    <div class="container">
        <?php if (!empty($error)) { ?>
            <div class="error-message">
                <?php echo $error; ?>
            </div>
        <?php } ?>
        <div class="book-details">
            <div class="book-cover">
                <img src="<?php echo $book['bookcover']; ?>" alt="<?php echo $book['title']; ?>">
            </div>
            <div class="book-info">
                <h1>
                    <?php echo $book['title']; ?>
                </h1>
                <p><strong>Author:</strong>
                    <?php echo $book['author']; ?>
                </p>
                <p><strong>Genre:</strong>
                    <?php echo $book['genre']; ?>
                </p>
                <p>
                    <?php echo $book['description'] ?? "unfortunately we don't have any description for this book"; ?>
                </p>

            </div>
        </div>

        <div class="reviews">
            <div class="post-review">
                <?php if ($_SESSION['logged_in']) { ?>
                    <h2>Post a Review</h2>
                    <form action="" method="post">
                        <label for="review">Your Review:</label>
                        <textarea id="review" name="review" required></textarea>
                        <button type="submit" class="button">Submit Review</button>
                    </form>
                <?php } ?>
            </div>
            <h2>Reviews</h2>
            <?php foreach ($reviews as $review): ?>
                <div class="review">
                    <h3>
                        <?php echo $review['user']; ?>
                    </h3>
                    <p>
                        <?php echo $review['review']; ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>