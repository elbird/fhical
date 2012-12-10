<?php

//TODO remove when finished
require_once $_SERVER['DOCUMENT_ROOT'] . '/fhical/icalapp/User.php';
session_start();
if(empty($_SESSION['user'])) {
	$user = User::retrieveUserByGoogleId("1234");
	$_SESSION['user'] = $user;
}

include($_SERVER['DOCUMENT_ROOT'] . '/fhical/icalapp/global.inc.php');

$error = array();
if(!empty($_SESSION['userDataFormError'])) {
	$error = $_SESSION['userDataFormError'];
	unset($_SESSION['userDataFormError']);
}
$data = array();
if(!empty($_SESSION['userDataFormData'])) {
	$data = $_SESSION['userDataFormData'];
	unset($_SESSION['userDataFormData']);
}
$currentPage = "userData";
$title = "User-Daten eingeben";
include($_SERVER['DOCUMENT_ROOT'] . '/fhical/header.inc.php');
?>
<article class="article clearfix">
	<div class="col_50">
		<form action="/fhical/saveUserData.php" method="POST" class="form">
			<h2><?php echo $title ?></h2>
			<p>
			<label for="key">
				<?php if (!empty($error['key'])): ?>
					<p class="warning">Bitte gib einen Key an</p>
				<?php endif; ?>
				Der Key mit dem der Token generiert wird die Zugangsdaten für das CIS auf dem Server verschlüsselt werden <br />
				<input id="key" type="text" name="key" <?php echo !empty($data['key']) ? 'value="' . $data['key'] . '"' : ''; ?>/>
			</label>
			</p>
			<p>
			<label for="username">
				<?php if (!empty($error['username'])): ?>
					<p class="warning">Bitte gib deinen CIS-Usernamen an</p>
				<?php endif; ?>
				Dein Username für das FH-Technikum-CIS<br />
				<input id="username" type="text" name="username" <?php echo $user->getTwUser() ? 'value="' . $user->getTwUser() . '"' : ''; ?>/>
			</label><br />
			<label for="password">
				<?php if (!empty($error['password'])): ?>
					<p class="warning">Bitte gib dein TW-Passwort ein</p>
				<?php elseif (!empty($error)): ?>
					<p class="warning">Passwort nicht vergessen ;)</p>
				<?php endif; ?>
				Dein Passwort für das FH-Technikum-CIS<br />
				<input id="password" type="password" name="password" />
			</label>
			</p>
			<div><button type="submit" class="button">Absenden</button></div>
		</form>
	</div>
</article>
<?php
include($_SERVER['DOCUMENT_ROOT'] . '/fhical/footer.inc.php');