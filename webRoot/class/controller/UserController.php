<?php
	
	if( !class_exists('User') ) {
		include(dirname(__FILE__) . '/../model/User.php');
		$User = new User;
	}
	
	class UserController extends User {
		
		function __construct($userId) {
			$userInfo = $this->getUserInfo($userId);
			
			$this->setUserId($userInfo['id']);
			$this->setName($userInfo['name']);
			$this->setUsername($userInfo['username']);
			$this->setEmail($userInfo['email']);
		}
		
		private function getUserInfo($userId) {
			$DB = $this->getDB();
			return $DB->getUserInfo($userId);
		}
		
	}
	
?>