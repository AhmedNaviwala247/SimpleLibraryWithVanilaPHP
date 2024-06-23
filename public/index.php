<?php



require "db.php";
include "commonfunc.php";

require_once __DIR__ . '/../vendor/autoload.php';
if (!$_SESSION['is_admin']) {
    $_SESSION['is_admin'] = false;
} else {
    $_SESSION['is_admin'] = true;
}
if (!$_SESSION['logged_in']) {
    $_SESSION['logged_in'] = false;
} else {
    $_SESSION['logged_in'] = true;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<?php

$pageNumber = isset($_GET["page"]) ? $_GET["page"] : 1;
$booksPerPage = 10;
$offset = ($pageNumber - 1) * $booksPerPage;

try {

    $stmt = $db->prepare("SELECT * FROM books ORDER BY id DESC LIMIT ?, ?");
    $stmt->bind_param("ii", $offset, $booksPerPage);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $books = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        $stmtCount = $db->prepare("SELECT COUNT(*) FROM books");
        $stmtCount->execute();
        $totalBooks = $stmtCount->get_result()->fetch_row()[0];
        $totalPages = ($totalBooks / $booksPerPage) + 1;
        $stmtCount->close();

    } else {
        echo 'Books Not Found';
    }
} catch (\Throwable $th) {
    echo "Something Went Wrong";
}
?>

<body>
    <div class="container">
        <div class="header">
            <h1>Simple Library</h1>
            <?php if ($_SESSION['is_admin']) { ?>
                <h1>Admin</h1>
            <?php } ?>
            <div>
                <?php if ($_SESSION['is_admin']) { ?>
                    <button class="btn" onclick="location.href='addbook.php'">Add New Book</button>
                <?php } ?>
                <?php if ($_SESSION['logged_in']) { ?>
                    <button class="btn" onclick="location.href='logout.php'">Logout </button>
                <?php } else { ?>
                    <button class="btn" onclick="location.href='signin.php'">Sign In </button>
                <?php } ?>
            </div>
        </div>
        <?php if ($pageNumber > $totalPages) { ?>
            <h1>You are far ahead, Please GO Back</h1>
        <?php } ?>
        <div class="card-container">
            <?php foreach ($books as $book): ?>
                <div class="card">
                    <img src="<?php echo $book['bookcover']; ?>" alt="<?php echo $book['title']; ?>">
                    <div class="card-content">
                        <h3>

                            <a href="book.php?bookId=<?= $book['id'] ?>"><?= $book['title']; ?></a>
                        </h3>
                        <p><strong>Author:</strong>
                            <?php echo $book['author']; ?>
                        </p>
                        <p><strong>Genre:</strong>
                            <?php echo $book['genre']; ?>
                        </p>
                    </div>
                    <?php if ($_SESSION['is_admin']) { ?>
                        <div>
                            <button class="card-btn"
                                onclick="window.location.href = 'editbook.php?bookId=<?= $book['id'] ?>'">Edit
                                Book</button>
                            <button class="card-btn"
                                onclick="window.location.href = 'deletebook.php?bookId=<?= $book['id'] ?>'">Delete
                                Book</button>

                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" <?php if ($i == $pageNumber)
                   echo 'class="active"'; ?>><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
    </div>

</body>


</html>