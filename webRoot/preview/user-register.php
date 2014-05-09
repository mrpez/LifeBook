<?php

	if( array_key_exists('email', $_POST) ) {
		if( !class_exists('MaintenanceController') ) {
			include(dirname(__FILE__) . '/../class/controller/MaintenanceController.php');
			$MaintenanceController = new MaintenanceController;
		}
		
		if( $MaintenanceController->registerUser($_POST['email'], $_POST['password'], $_POST['username'], $_POST['name']) ) {
			header('Location:/register-success.lb');
			die;
		}
	}

?>