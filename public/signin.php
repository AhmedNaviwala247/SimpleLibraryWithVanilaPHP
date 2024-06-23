<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/styles.css">
    <title>Sign In</title>
</head>


<?php
include "commonfunc.php";

$error = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $error = signin($email, $password);
    if ($error === true) {
        header('location: index.php');
        exit;
    }
}


?>

<body>
    <div class="container-center">
        <div class="container-w400">
            <h1>Sign In</h1>
            <?php if (!empty($error)) { ?>
                <div class="error-message">
                    <?php echo $error; ?>
                </div>
            <?php } ?>
            <form action="" method="post">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Log in</button>
            </form>
            <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
            <!-- <br> </br> -->
            <h1>OR</h1>

            <a href="glogin.php" class="button">Sign in with Google</a>
        </div>
    </div>
</body>

</html>