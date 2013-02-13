<?php

include(dirname(__FILE__) . '/../inc/global.inc.php');

if(!empty($_SESSION['setOptionsFormError'])) {
	unset($_SESSION['setOptionsFormError']);
}
$error = array();

$optionsConfig = array(
		"stg_kz" => 'integer',
		"sem" => 'integer',
		"ver" => 'string',
		"begin" => 'date',
		"ende" => 'date'

	);

$options = $user->getOptions();

if(empty($options)) {
	$options = array();
}


foreach ($optionsConfig as $name => $type) {
	if(isset($_POST[$name])) {
		$optionValue = NULL;
		switch ($type) {
			case 'integer':
				$optionValue = filter_var($_POST[$name], FILTER_SANITIZE_NUMBER_INT);
				break;
			case 'date':
				//date validation
				$optionValue = DateTime::createFromFormat('j.m.Y', $_POST[$name]);
				if(!$optionValue) {
					$error[$name] = true;
				} else {
					$optionValue = $optionValue->format('U');
				}
				break;
			case 'string':
			default:
				$optionValue = filter_var($_POST[$name], FILTER_SANITIZE_STRING);
				break;
		}
		if(!empty($optionValue) || $optionValue === 0) {
			$options[$name] = $optionValue;
		} elseif ( isset($options[$name]) ) {
			unset($options[$name]);
		}
	}
}

if(!empty($options)) {
	$user->setOptions($options);
	$user->save();
}

if(!empty($error)) {
	$_SESSION['setOptionsFormError'] = $error;
}

header('Location: http://' . $_SERVER['HTTP_HOST'] . '/fhical/setOptionsForm.php');
die();

