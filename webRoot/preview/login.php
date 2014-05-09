<?php
	
	if( array_key_exists('username', $_POST) ) {
		if( !class_exists('LoginController') ) {
			include(dirname(__FILE__) . '/../class/controller/LoginController.php');
			$LoginController = new LoginController;
		}
		
		$LoginController->login($_POST['username'], $_POST['password']);
	}
	
?>