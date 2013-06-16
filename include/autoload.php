<?php
// Fonction d'autoload des Classes
function __autoload($class){
	$path = '';
	$self = basename($_SERVER['PHP_SELF']);
	require_once($path.'class/'.$class.'.class.php');
}