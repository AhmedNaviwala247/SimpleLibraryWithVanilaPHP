<?php

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbName = "my_db";



$db = mysqli_connect($servername, $username, $password, $dbName);

// define("$db", $db);

if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}