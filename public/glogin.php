<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Google\Client;
use Google\Service\Oauth2;

$client = new Client();
$client->setClientId("927261942785-7dvm38bf6o1iqp2b7o8ojl6bqlql53kp.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost:8000/callback.php");
$client->setAccessType('offline');
$client->setApprovalPrompt('force');
$client->addScope(Oauth2::USERINFO_EMAIL);
$client->addScope(Oauth2::USERINFO_PROFILE);
// $client->addScope(Google\Service\Drive::DRIVE_METADATA_READONLY);

$authUrl = $client->createAuthUrl();

header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
exit;