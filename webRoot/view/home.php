<h2>Home!</h2>

<?php
	
	if( $LoginController->isLoggedIn() ) {
		echo '<a href="/notes.lb">Go to Notes</a>';
	}
	
?>