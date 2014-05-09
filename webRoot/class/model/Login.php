<?php

if( !class_exists('Utility') ) {
	include( dirname(__FILE__) . '/../Utility.php');
	$Utility = new Utility;
}

class Login extends Utility {
	
	private $userId;
	private $isLoggedIn;
	
	public function setUserId($userId) {
		$this->userId = $userId;
		$this->isLoggedIn = true;
		return true;
	}
	
	public function killLogin() {
		$this->isLoggedIn = false;
	}
	
	public function getUserId() {
		return $this->userId;
	}
	
	public function isLoggedIn() {
		return $this->isLoggedIn;
	}
	
}
	
?>