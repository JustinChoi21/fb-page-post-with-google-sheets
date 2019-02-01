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

if (isset($_GET['state'])) { $helper->getPersistentDataHandler()->set('state', $_GET['state']); }

try {
    $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (! isset($accessToken)) {
    if ($helper->getError()) {
        header('HTTP/1.0 401 Unauthorized');
        echo "Error: " . $helper->getError() . "\n";
        echo "Error Code: " . $helper->getErrorCode() . "\n";
        echo "Error Reason: " . $helper->getErrorReason() . "\n";
        echo "Error Description: " . $helper->getErrorDescription() . "\n";
    } else {
        header('HTTP/1.0 400 Bad Request');
        echo 'Bad request';
    }
    exit;
}

// Logged in
echo '<h3>Access Token</h3>';
echo $accessToken->getValue();
echo '<br/>';
echo '<p>Metadata에서 access token type 확인(ex. USER), Expire_at을 확인하세요. 그리고 여기서 받은 Access Token을 facebook_auth.ini 에 붙이세요.</p>';

// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
echo '<h3>Access Token Metadata</h3>';
var_dump($tokenMetadata);

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId($app_id);
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {
    // Exchanges a short-lived access token for a long-lived one
    try {
        $longAccessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo "<p>Error getting long-lived access token: " . $e->getMessage() . "</p>\n\n";
        exit;
    }

    echo '<h3>Long-lived Access Token</h3>';
    echo $longAccessToken->getValue();
    echo '<a href="https://developers.facebook.com/docs/facebook-login/access-tokens#termtokens">단기 토큰과 장기 토큰의 차이에 대해</a>';

    $LongTokenMetadata = $oAuth2Client->debugToken($longAccessToken);
    echo '<h3>Long-lived Access Token Metadata</h3>';
    var_dump($LongTokenMetadata);
}

$_SESSION['fb_access_token'] = (string) $accessToken;


// User is logged in with a long-lived access token.
// You can redirect them to a members-only page.
//header('Location: https://example.com/members.php');