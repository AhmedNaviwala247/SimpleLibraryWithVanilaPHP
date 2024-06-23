<?php
session_start();
if (!isset($_COOKIE['email'])) {
    header('location: signin.php');
    exit;

}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Not Admin</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>


<body>
    <div class="container-center">
        <h1>You are Not Admin, kindly go Back</h1>
        <a href="index.php" class="button">Go to Homepage</a>
    </div>
</body>

</html>