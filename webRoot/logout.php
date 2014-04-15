<?php
	
	if( !class_exists('Utility') ) {
		include(dirname(__FILE__) . '/class/Utility.php');
		$Utility = new Utility;
	}
	
	$Utility->logout();
	
	header('Location: /life/login.php', true, 302);
	die;
?>