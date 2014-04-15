<?php
	
	date_default_timezone_set( 'America/New_York' );
	
	class Utility {
	
		public function getPDO() {
			$dbCredentials = array(1);
			$dbCredentials[ 'server' ] = 'localhost';
			$dbCredentials[ 'database' ] = 'lifebook';
			$dbCredentials[ 'u' ] = 'root';
			$dbCredentials[ 'p' ] = '';
			
			try {
				if( !isSet($PDODB) )
				{
					$PDODB = new PDO("mysql:host=" . $dbCredentials[ 'server' ] . ";dbname=" . $dbCredentials[ 'database' ], $dbCredentials[ 'u' ], $dbCredentials[ 'p' ]);
				}
			} catch (PDOException $e) {
				$error = $e->getMessage();
				Utility::throwError(1, '', $error, true);
			}
			
			return $PDODB;
		}
		
		public function validateLogin() {
			if( !$this->checkLogin() ) {
				header('Location: /login.php', true, 302);
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
		
	}
?>