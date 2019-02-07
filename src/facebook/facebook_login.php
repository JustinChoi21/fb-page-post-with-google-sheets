<?php

if(!session_id()) {
    session_start();
}

require_once "../../vendor/autoload.php";

$config = parse_ini_file("facebook_auth.ini");

$app_id = $config['app_id'];
$app_secret = $config['app_secret'];

try {
    $fb = new Facebook\Facebook([
        'app_id' => $app_id,
        'app_secret' => $app_secret,
        'default_graph_version' => 'v3.2',
    ]);
} catch (\Facebook\Exceptions\FacebookSDKException $e) {
}

$helper = $fb->getRedirectLoginHelper();

$permissions = ['manage_pages', 'publish_pages']; // Optional permissions

// You should change LoginUrl to yours.
$loginUrl = $helper->getLoginUrl('http://localhost/auto-post-on-facebook-page-with-google-spreadsheets/src/facebook/facebook_login_callback.php', $permissions);

echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';