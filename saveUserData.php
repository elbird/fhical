<?php

include($_SERVER['DOCUMENT_ROOT'] . '/fhical/icalapp/global.inc.php');

$error = array();
$data = array();
// check if all needed variables are present
if (empty($_POST['key'])) {
	$error['key'] = true;
} else {
	$key = filter_var($_POST['key'], FILTER_SANITIZE_STRING);
	$data['key'] = $key;
}

if (empty($_POST['username'])) {
	$error['username'] = true;
} else {
	$user->setTwUser(filter_var($_POST['username'], FILTER_SANITIZE_STRING));
}

if (empty($_POST['password'])) {
	$error['password'] = true;
}

//if there have been errors redirect back to the form
if(!empty($error)) {
	$_SESSION['userDataFormError'] = $error;
	$_SESSION['userDataFormData'] = $data;
	header('Location:  http://' . $_SERVER['HTTP_HOST'] . '/fhical/userDataForm.php');
	die();
}

// if we are here those session variables should not be set anymore
if(isset($_SESSION['userDataFormError'])) {
	unset($_SESSION['userDataFormError']);
}
if(isset($_SESSION['userDataFormData'])) {
	unset($_SESSION['userDataFormData']);
}

//1. salt the passwords a bit with random values to make it harder for a known plaintext attack (if the attacker knows the iv)
//2. concatinate password with random values - use a delimeter ";"
$data = base64_encode(mcrypt_create_iv (16)) . ";"
			. base64_encode($_POST['password']) . ";"
			. base64_encode(mcrypt_create_iv (16));

//3. encrypt the password
$td = mcrypt_module_open('rijndael-256', '', 'cbc', '');
// create iv
$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td));
// get max key size
$ks = mcrypt_enc_get_key_size($td);
// generate a "better" key from the users original key
$hashedKey = substr(hash('sha512', $key), 0, $ks);
mcrypt_generic_init($td, $hashedKey, $iv);
$encrypted = mcrypt_generic($td, $data);
mcrypt_generic_deinit($td);
mcrypt_module_close($td);

//4. save the encrypted password and the iv to the user and save the user to the db
$user->setEncryptedPass($encrypted);
$user->setEncryptionIv($iv);
$user->save();

//5. save the key to the session and redirect
$_SESSION['key'] = $key;

header('Location: http://' . $_SERVER['HTTP_HOST'] . '/fhical/setOptionsForm.php');

