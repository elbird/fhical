<?php
require_once(dirname(__FILE__) . '/icalapp/User.php');
require_once(dirname(__FILE__) . '/icalapp/config.php');
$config = Config::get();

if (empty($_GET['user']) || empty($_GET['key'])) {
	die("Please provide a user and a key!");
}

$user = User::retrieveUserById(filter_var($_GET['user'], FILTER_SANITIZE_NUMBER_INT));

if (!$user || !$user->getTwUser() || !$user->getEncryptedPass() || !$user->getEncryptionIv()) {
	die("User not found, or no encrypted password stored");
}

$key = filter_var($_GET['key'], FILTER_SANITIZE_STRING);

//3. encrypt the password
$td = mcrypt_module_open('rijndael-256', '', 'cbc', '');
$ks = mcrypt_enc_get_key_size($td);
$hashedKey = substr(hash('sha512', $key), 0, $ks);
mcrypt_generic_init($td, $hashedKey,  $user->getEncryptionIv()); 

/* Decrypt encrypted string */
$decrypted = mdecrypt_generic($td, $user->getEncryptedPass());

/* Terminate decryption handle and close module */
mcrypt_generic_deinit($td);
mcrypt_module_close($td);

$decrypted = explode(';', $decrypted);
if(!empty($decrypted[1])) {
	$password = base64_decode($decrypted[1]);

}

if(empty($password)) {
	header('HTTP/1.0 401 Unauthorized', true, 401);
	die();
}

header("Content-Type: text/calendar");
header("Content-disposition: filename=FH-Kalender_09_2012_ical.ics");
$url = "https://" . $user->getTwUser() . ":" . $password . '@cis.technikum-wien.at/cis/private/lvplan/stpl_kalender.php?';

$options = array('pers_uid' => $user->getTwUser());
$options = array_merge($options, $config['icalUrlBaseParams'], $user->getOptions());
$url = $url . http_build_query($options);

$ch = curl_init($url);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, true);
$ical = curl_exec($ch);
curl_close($ch);
$ical = str_replace("PRODID:FH Technikum Wien", "PRODID:FH Technikum WiennX-WR-TIMEZONE:Europe/Vienna", $ical);
echo $ical;
