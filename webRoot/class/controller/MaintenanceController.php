<?php

if( !class_exists('Utility') ) {
	include(dirname(__FILE__) . '/../Utility.php');
	$Utility = new Utility;
}

class MaintenanceController extends Utility {
	
	public function registerUser($email, $password, $username, $name) {
		$DB = $this->getDB();
		
		$password = $this->hashPassword($password);
		
		return $DB->registerUser($email, $password, $username, $name);
		
	}
	
	
}

?>