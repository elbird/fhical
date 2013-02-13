<?php
require_once(dirname(__FILE__) . '/icalapp/google-api-php-client/src/Google_Client.php');
require_once(dirname(__FILE__) . '/icalapp/google-api-php-client/src/contrib/Google_Oauth2Service.php');
require_once(dirname(__FILE__) . '/icalapp/google-api-php-client/src/contrib/Google_UrlshortenerService.php');
require_once(dirname(__FILE__) . '/icalapp/User.php');
require_once(dirname(__FILE__) . '/icalapp/config.php');
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
  unset($_SESSION['user']);
  unset($_SESSION['token']);
  $client->revokeToken();
}

if ($client->getAccessToken()) {
  $userInfo = $oauth2->userinfo->get();

  // These fields are currently filtered through the PHP sanitize filters.
  // See http://www.php.net/manual/en/filter.filters.sanitize.php
  $id = filter_var($userInfo['id'], FILTER_SANITIZE_NUMBER_INT);

  try {
    $myUser = User::retrieveUserByGoogleId($id);
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
  $_SESSION['user'] = $myUser;
} else {
  $authUrl = $client->createAuthUrl();
}

if(!empty($_SESSION['user'])) {
  $user = $_SESSION['user'];
}
$currentPage = "home";
$title = "Home";
include(dirname(__FILE__) . '/inc/header.inc.php');
?>

<article class="article clearfix">
  <div class="col_50">
    <h1>FH Technikum Wien - Ical Downloader (für Google Calendar)</h1>
    <?php if(isset($_SESSION['notLoggedIn'])): ?>
    <p class="message">Bitte logge dich ein!</p>
    <?php unset($_SESSION['notLoggedIn']);
          endif; ?>
    <?php if(isset($authUrl)): ?>
        <p>Um den Kalender Dowload verwenden zu können musst du dich erst einloggen:<br />
          <a class='button' href='<?php echo $authUrl; ?>'>Login mit Google</a>
        </p>
    <? elseif ($_SESSION['user']): ?>
      <h2>Hallo <?php echo $user->getName(); ?>!</h2>
       <ul>
         <li>Hier kannst du deine Userdaten ändern: <a href="userDataForm.php"></a> </li>
         <li>Hier kannst du die URL einsehen und die Optionen anpassen: <a href="setOptionsForm.php"></a> </li>
       </ul>
    <? endif; ?>
  </div>
</article>
<?php
include(dirname(__FILE__) . '/inc/footer.inc.php');
