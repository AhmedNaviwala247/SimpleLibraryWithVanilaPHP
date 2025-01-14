<?php

session_start();


require_once __DIR__ . '/../vendor/autoload.php';
require_once "commonfunc.php";
require_once "secret.php";




$client = new Google\Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri("http://localhost:8000/callback.php");
$client->setAccessType('offline');
$client->setApprovalPrompt('force');
$client->setHttpClient(new GuzzleHttp\Client([
    'verify' => false,
]));


if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    // print_r($token);
    // exit;

    if (isset($token['access_token'])) {
        $client->setAccessToken($token['access_token']);

        $oauth2 = new Google\Service\Oauth2($client);
        $userInfo = $oauth2->userinfo->get();

        $refresh_token = $token['refresh_token'];
        $accessToken = $token['access_token'];
        $full_name = $userInfo->getName();
        $email = $userInfo->getEmail();

        $_SESSION['access_token'] = $accessToken;
        $_SESSION['refresh_token'] = $refresh_token;


        $error = googleLogin(
            email: $email,
            full_name: $full_name,
            refresh_token: $refresh_token,
        );
        if ($error === true) {
            header('location: index.php');
            exit;
        } else {
            echo $error;
        }
    }

}

// Handle the error response
if (isset($_GET['error'])) {
    echo 'Error: ' . $_GET['error'];
}