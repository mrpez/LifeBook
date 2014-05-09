<?php
	
	if( !class_exists('LoginController') ) {
		include(dirname(__FILE__) . '/../class/controller/LoginController.php');
		$LoginController = new LoginController;
	}
	
	$LoginController->logout();
	
?>