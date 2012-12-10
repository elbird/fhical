<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/fhical/icalapp/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/fhical/icalapp/config.php';
$config = Config::get();

//TODO remove the if when going to production - session should always start here
if(session_id() == '') {
    // session isn't started
    session_start();
}

if(empty($_SESSION['user']) || empty($_SESSION['token'])) {
	$_SESSION['notLoggedIn'] = true;
	header('Location: http://' . $_SERVER['HTTP_HOST'] . '/fhical/index.php');
	die();
}

if(isset($_SESSION['notLoggedIn'])) {
	unset($_SESSION['notLoggedIn']);
}

$user = $_SESSION['user'];
