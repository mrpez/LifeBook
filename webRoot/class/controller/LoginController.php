<?php

if( !class_exists('Login') ) {
	include( dirname(__FILE__) . '/../model/Login.php');
	$Login = new Login;
}

class LoginController extends Login {
	
	function __construct() {
		if( session_id() == null ) {
			session_start();
		}
		
		if( array_key_exists('userId', $_SESSION)
			&& array_key_exists('isLoggedIn', $_SESSION)
			&& $_SESSION['isLoggedIn']) {
			$this->setUserId($_SESSION['userId']);
		} else {
			$this->killLogin();
		}
	}
	
	public function login($email, $password) {
		$DB = $this->getDB();
		
		$password = $this->hashPassword($password);
		
		$user_id = $DB->verifyLogin($email, $password);
		
		if( $user_id === false ) {
			return false;
		} else {
			$_SESSION['userId'] = $user_id;
			$_SESSION['isLoggedIn'] = true;
			header('Location:/');
			die;
		}
	}
	
	public function logout() {
		$_SESSION['userId'] = null;
		$_SESSION['isLoggedIn'] = false;
	}
	
}

?>