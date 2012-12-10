<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/fhical/icalapp/User.php';

$user = new User("1234", "Sebastian", "sebb@gmx.at");
var_dump($user);
$user->save();

$retrievedUser = User::retrieveUserByGoogleId("1234");
var_dump($retrievedUser);
