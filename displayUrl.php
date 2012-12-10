<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/fhical/icalapp/User.php';

session_start();

if(empty($_SESSION['user'])) {
	header('Location: http://' . $_SERVER['HTTP_HOST'] . '/fhical/index.php');
	die();
}
$user = $_SESSION['user'];

// a user without a key should not see this
if(empty($_SESSION['key'])) {
	header('Location: http://' . $_SERVER['HTTP_HOST'] . '/fhical/generateUrlForm.php');
	die();
}
$key = $_SESSION['key'];

$url = 'http://' . $_SERVER['HTTP_HOST'] . '/fhical/getIcal.php?user=' . $user->getId() . '&key=' . urlencode($key);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<style type="text/css">
			.error {
				color: red;
			}
		</style>
	</head>
	<body>
		<div id="wrapper">
			<a href="<?php echo $url; ?>"><?php echo $url; ?></a>
		</div>
	</body>
</html>