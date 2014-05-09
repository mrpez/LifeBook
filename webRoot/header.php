<?php
	if( !class_exists('Utility') ) {
		include(dirname(__FILE__) . '/class/Utility.php');
		$Utility = new Utility;
	}
	
	if( !class_exists('LoginController') ) {
		include(dirname(__FILE__) . '/class/controller/LoginController.php');
		$LoginController = new LoginController;
	}
	
?>
<!DOCTYPE HTML>
<html>
<head>
	<title>LifeBook</title>
	<link href='/lib/css/main.css' rel='stylesheet' type='text/css'>
	
</head>
<body>
<div id="headerContainer">
	<div id="headerContent">
		<div id="headerLeft">
			<a href="/" style="text-decoration:none;"><h1>LifeBook</h1></a>
		</div>
		<div id="headerRight">
			<?php
				if( $LoginController->isLoggedIn() ) {
					if( !class_exists('UserController') ) {
						include(dirname(__FILE__) . '/class/controller/UserController.php');
						$UserController = new UserController($LoginController->getUserId());
					}
					echo 'Welcome, ' . $UserController->getName() . ' | <a href="/logout.lb">Logout</a>';					
				} else {
					echo '<a href="/login.lb">Login</a>';
				}
			?>
		</div>
		<div class="clearFix"></div>
	</div>
</div>
<div id="bodyContainer">
	<div id="bodyContent">