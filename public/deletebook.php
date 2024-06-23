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


include "db.php";

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["bookId"])) {

    $bookId = $_GET["bookId"];

    try {
        $query = "DELETE FROM books WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('i', $bookId);
        if ($stmt->execute()) {
            header('location: index.php');
            exit;
        } else {
            echo "something went wrong";
        }

    } catch (\Exception $e) {
        echo $e;
        return false;
    }
}
?>