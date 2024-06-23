<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
    <title>Sign Up</title>
</head>

<?php

include "commonfunc.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];


    $error = signup($password, $email, $full_name);
    if ($error === true) {
        header('location: index.php');
        exit;
    }
}


?>


<body>
    <div class="container-center">
        <div class="container-w400">
            <h1>Sign Up</h1>
            <?php if (!empty($error)) { ?>
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
            <?php } ?>
            <form action="" method="post">
                <input type="text" name="full_name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Sign Up</button>
            </form>
            <p>Already Have an account? <a href="signin.php">Sign In</a></p>
        </div>
    </div>
</body>

</html>