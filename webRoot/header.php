<?php
	if( !class_exists('Utility') ) {
		include(dirname(__FILE__) . '/class/Utility.php');
		$Utility = new Utility;
	}
	
	$Utility->validateLogin();
?>
<!DOCTYPE HTML>
<html>
<head>

</head>
<body>