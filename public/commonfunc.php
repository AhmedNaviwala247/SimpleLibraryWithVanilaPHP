<?php

session_start();
include "db.php";


/**
 * this function will verify email and password 
 * if it is verified function will save session and cookie that user is logged in and 
 * if any error occur function will return error in that string
 **/
function signin(string $email, string $password) : string|bool
{
    try {

        global $db;
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            $user = mysqli_fetch_assoc($result);
            if (!$user) {
                return "Invalid Email, Please Try Again";

            }
        } else {
            return "something went wrong";

        }
        if (password_verify($password, $user['password'])) {

            $_SESSION['logged_in'] = true;

            if ($user['is_admin']) {
                $_SESSION['is_admin'] = true;
            }

            setcookie('email', $email, time() + (86400 * 30), '/');

            return true;
        } else {
            return "Invalid email or password";
        }
    } catch (\Exception $e) {
        return $e->getMessage();
    }
}
/**
 * this function will hash the password and will store all the info in SQL DB 
 * basically create a user and 
 * then it will redirect password and email into signin
 **/
function signup(string $password, string $email, string $full_name) : bool|string
{
    global $db;
    try {

        $date = date("Y-m-d H:i:s");

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (full_name, email, password, created_at) 
        VALUES (?, ?, ?, ?)";

        $stmt = $db->prepare($query);
        $stmt->bind_param('ssss', $full_name, $email, $hashed_password, $date);
        if ($stmt->execute()) {
        } else {
            return "Something went wrong.";

        }

    } catch (\Exception $e) {
        return $e->getMessage();
    }

    return signin($email, $password);
}
/**
 * this function will first find email from DB if it finds one then user will be logged into site 
 * but if it don't find any email function will create a user and store fullname email and refreshtoken 
 * and function will save session and cookie that user is logged in
 * if any error occur function will return error in that string
 **/
function googleLogin(string $full_name, string $email, string $refresh_token) : string|bool
{
    global $db;
    try {
        $stmtlogin = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmtlogin->bind_param("s", $email);
        $stmtlogin->execute();
        $result = $stmtlogin->get_result();

        if ($result) {
            $user = mysqli_fetch_assoc($result);

            if (!is_null($user['password'])) {
                return "User already exist, please use sign in form";
            }


            if (!is_null($user)) {
                $stmtUPDToken = $db->prepare("UPDATE users SET refresh_token = ? WHERE id = {$user['id']}");
                $stmtUPDToken->bind_param("s", $refresh_token);
                if ($stmtUPDToken->execute()) {
                } else {
                    return "access token was not updated";
                }
            }


            if (is_null($user)) {
                $date = date("Y-m-d H:i:s");

                $query = "INSERT INTO users (full_name, email, refresh_token, created_at) 
                    VALUES (?, ?, ?, ?)";

                $stmt = $db->prepare($query);
                $stmt->bind_param('ssss', $full_name, $email, $refresh_token, $date);
                if ($stmt->execute()) {
                } else {
                    return "Please Try Again ";
                }
            }
            $_SESSION['logged_in'] = true;


            if ($user['is_admin']) {
                $_SESSION['is_admin'] = true;
            }

            setcookie('email', $email, time() + (86400 * 30), '/');

            return true;

        } else {
            return "Invalid email or password";
        }
    } catch (\Exception $e) {
        return $e->getMessage();
    }
}