<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/fhical/icalapp/google-api-php-client/src/Google_Client.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/fhical/icalapp/google-api-php-client/src/contrib/Google_Oauth2Service.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/fhical/icalapp/google-api-php-client/src/contrib/Google_UrlshortenerService.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/fhical/icalapp/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/fhical/icalapp/config.php';
session_start();

$client = new Google_Client();
$client->setApplicationName("Google UserInfo PHP Starter Application");
// Visit https://code.google.com/apis/console?api=plus to generate your
// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
$config = Config::get();
$client->setClientId($config['clientId']);
$client->setClientSecret($config['clientSecret']);
$client->setRedirectUri($config['redirectUri']);
$client->setDeveloperKey($config['developerKey']);
$oauth2 = new Google_Oauth2Service($client);
$urlshortener = new Google_UrlshortenerService($client);

if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['token'] = $client->getAccessToken();
  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
  return;
}

if (isset($_SESSION['token'])) {
 $client->setAccessToken($_SESSION['token']);
}

if (isset($_REQUEST['logout'])) {
  unset($_SESSION['token']);
  $client->revokeToken();
}

if ($client->getAccessToken()) {
  $userInfo = $oauth2->userinfo->get();

  // These fields are currently filtered through the PHP sanitize filters.
  // See http://www.php.net/manual/en/filter.filters.sanitize.php
  $id = filter_var($userInfo['id'], FILTER_SANITIZE_NUMBER_INT);

  try {
    $myUser = User::retrieveUserById($id);
  } catch (Exception $e) {
    echo $e->getMessage();
    exit();
  }
  if(empty($myUser)) {
    $email = filter_var($userInfo['email'], FILTER_SANITIZE_EMAIL);
    $name = filter_var($userInfo['name'], FILTER_SANITIZE_STRING);
    $myUser = new User($id, $name, $email);
    try {
      $myUser->save();
    } catch (Exception $e) {
      echo $e->getMessage();
      exit();
    }
  }
  // The access token may have been updated lazily.
  $_SESSION['token'] = $client->getAccessToken();
} else {
  $authUrl = $client->createAuthUrl();
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"></head>
<body>
<header><h1>Google UserInfo Sample App</h1></header>
<?php if(isset($personMarkup)): ?>
<?php print $personMarkup ?>
<?php 

//$url = new Google_Url();
//$url->longUrl = "https://developers.google.com/accounts/docs/OAuth2Login#overview";
//$short = $urlshortener->url->insert($url);
//var_dump($short);
?>
<?php endif ?>
<?php
if(isset($myUser)) {
  var_dump($myUser);
}
?>
<?php
  if(isset($authUrl)) {
    print "<a class='login' href='$authUrl'>Connect Me!</a>";
  } else {
   print "<a class='logout' href='?logout'>Logout</a>";
  }
?>
</body></html>