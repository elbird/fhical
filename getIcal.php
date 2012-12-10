<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/fhical/icalapp/User.php';

if (empty($_GET['user']) || empty($_GET['key'])) {
	die("Please provide a user and a key!");
}

$user = User::retrieveUserById(filter_var($_GET['user'], FILTER_SANITIZE_NUMBER_INT));

if (!$user || !$user->getEncryptedPass() || !$user->getEncryptionIv()) {
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
	echo $password;
}


