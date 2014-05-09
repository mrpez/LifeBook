<?php

if( !class_exists('Utility') ) {
	include( dirname(__FILE__) . '/../Utility.php');
	$Utility = new Utility;
}

class User extends Utility {
	
	private $userId;
	private $email;
	private $name;
	private $username;
	
	public function setUserId($userId) {
		$this->userId = $userId;
		return true;
	}
	public function setName($name) {
		$this->name = $name;
		return true;
	}
	public function setEmail($email) {
		$this->email = $email;
		return true;
	}
	public function setUsername($username) {
		$this->username = $username;
		return true;
	}
	
	public function getUserId() {
		return $this->userId;
	}
	public function getName() {
		return $this->name;
	}
	public function getEmail() {
		return $this->email;
	}
	public function getUsername() {
		return $this->username;
	}
	
}

?>