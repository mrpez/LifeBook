<?php
	if( array_key_exists('u', $_POST)
		&& array_key_exists('p', $_POST) )
	{
		if( $_POST['u'] == 'ryan'
			&& $_POST['p'] == 'rty123qwe~' )
		{
			if( !class_exists('Utility') ) {
				include(dirname(__FILE__) . '/class/Utility.php');
				$Utility = new Utility;
			}
			
			$Utility->createLogin();
			
			header('Location: /life/', true, 302);
			die;
		}
	}
?>
<!DOCTYPE HTML>
<html>
<head>
</head>
<body>
	<h1>Login</h1>
	<form method="post" action="">
		<table>
			<tr>
				<td>User</td>
				<td><input type="text" name="u"/></td>
			</tr><tr>
				<td>Pass</td>
				<td><input type="password" name="p"/></td>
			</tr><tr>
				<td colspan="2"><input type="submit" value="Login"/></td>
			</tr>
		</table>
	</form>
</body>
</html>