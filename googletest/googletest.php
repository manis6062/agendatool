<?php
session_start();
require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_CalendarService.php';
require_once 'src/contrib/Google_AnalyticsService.php';

$scriptUri = "http://".$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF'];

$client = new Google_Client();
$client->setAccessType('online'); // default: offline
$client->setApplicationName('Agenda Tool');
//$client->setClientId('302894062557-4bblcb2hjhi49gpin4a35t6qeracmjm4.apps.googleusercontent.com');
$client->setClientId('1037618522304-vt03765a04ocmpohl9bg7ebit5t3nple.apps.googleusercontent.com');
//$client->setClientSecret('XhtLjyCcBYOvl0u7fTK0VnD2');
$client->setClientSecret('-dKpr2DtEJ4u1L5TRCwEUDb7');
$client->setRedirectUri($scriptUri);
//$client->setDeveloperKey('AIzaSyDXTByvpjjf4LSpCLEDr0O87LGvY33LX3A'); // API key
$client->setDeveloperKey('AIzaSyCLUn7LAVG4WK3tqQlQtAidtlwOgIM3zrY'); // API key

// $service implements the client interface, has to be set before auth call
$service = new Google_AnalyticsService($client);
$service = new Google_CalendarService($client);


if (isset($_GET['logout'])) { // logout: destroy token
    unset($_SESSION['token']);
	die('Logged out.');
}

if (isset($_GET['code'])) { // we received the positive auth callback, get the token and store it in session
    $client->authenticate();
    $_SESSION['token'] = $client->getAccessToken();
}

if (isset($_SESSION['token'])) { // extract token from session and configure client
    $token = $_SESSION['token'];
    $client->setAccessToken($token);
}

if (!$client->getAccessToken()) { // auth call to google
    $authUrl = $client->createAuthUrl();
    header("Location: ".$authUrl);
    die;
}
echo 'Hello, world.';