<?php
	
	date_default_timezone_set( 'America/New_York' );
	
	class Utility {
		private $DB = null;
		private $PDODB = null;
	
		public function getDB() {
			if( $this->DB == null ) {
				if( !class_exists('MySQLDB') )
					include(dirname(__FILE__) . '/MySQLDB.php');
				$this->DB = new MySQLDB;
			}
			
			return $this->DB;
		}
	
		public function getPDO() {
			$dbCredentials = array(1);
			$dbCredentials[ 'server' ] = 'localhost';
			$dbCredentials[ 'database' ] = 'lifebook';
			$dbCredentials[ 'u' ] = 'root';
			$dbCredentials[ 'p' ] = '';
			
			try {
				if( $this->PDODB == null )
				{
					$this->PDODB = new PDO("mysql:host=" . $dbCredentials[ 'server' ] . ";dbname=" . $dbCredentials[ 'database' ], $dbCredentials[ 'u' ], $dbCredentials[ 'p' ]);
				}
			} catch (PDOException $e) {
				$error = $e->getMessage();
				$this->throwError(1, '', $error, true);
			}
			
			return $this->PDODB;
		}
		
		public function validateLogin() {
			if( !$this->checkLogin() ) {
				header('Location: /login.lb', true, 302);
				die;
			}
		}
		
		public function createLogin() {
			if(session_id() == '' || !isset($_SESSION)) {
				// session isn't started
				session_start();
			}
			
			$_SESSION['loginDate'] = date('U');
			setcookie('h', $this->getLoginHash($_SESSION['loginDate']));
		}
		
		public function logout() {
			setcookie('h', null, time());
			$_SESSION['loginDate'] = null;
		}
		
		private function checkLogin() {
			if(session_id() == '' || !isset($_SESSION)) {
				// session isn't started
				session_start();
			}
			
			if( !array_key_exists('h', $_COOKIE)
				|| !array_key_exists('loginDate', $_SESSION) 
				|| $_COOKIE['h'] != $this->getLoginHash($_SESSION['loginDate'])
				|| (date('U') - $_SESSION['loginDate']) > 604800000) {
				return false;
			}
			
			return true;			
		}
				
		private function getLoginHash($in) {
			return md5('42323SDFkjHKFjhaiu&*^(&*@#"' . $in);
		}
		
		public function hashPassword($pass) {
			return md5('42323SDFkjHKFjhaiu&*^(&*@#"' . $pass . 'adsfasd(*&)(*&(%&^I&T&^RI&TYRYDSAIU');
		
		}
		
		public function throwError($var1, $var2 = null, $var3 = null, $var4 = null, $var5 = null, $var6 = null, $var7 = null, $var8 = null) {
			echo '<pre>';
			var_dump($var1);
			var_dump($var2);
			var_dump($var3);
			var_dump($var4);
			var_dump($var5);
			var_dump($var6);
			var_dump($var7);
			var_dump($var8);
			echo '</pre>';
		}
		
	}
?>