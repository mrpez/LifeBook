<?php
	
	include(dirname(__FILE__) . '/../class/Utility.php');
	$Utility = new Utility;
	
	$PDODB = $Utility->getPDO();
	
	$q = $PDODB->prepare("CREATE TABLE users
						  (
							id integer not null auto_increment primary key
							, email varchar(200)
							, password varchar(255)
							, username varchar(100)
							, name varchar(200)
						  );");
	if( !$q->execute() ) {
		var_dump($q->errorInfo());
	}
	
	$q = $PDODB->prepare("CREATE TABLE notes
						  (
							id integer not null auto_increment primary key
							, title varchar(200)
							, note varchar(5000)
							, last_updated timestamp DEFAULT current_timestamp()
						  );");
	if( !$q->execute() ) {
		var_dump($q->errorInfo());
	}
	
	$q = $PDODB->prepare("CREATE TABLE category_tree
						  (
							id integer not null auto_increment primary key
							, user_id integer
							, note_id integer
							, left_pointer integer
							, right_pointer integer
							, FOREIGN KEY (user_id) REFERENCES users(id)
							, FOREIGN KEY (note_id) REFERENCES notes(id)
						  );");
	if( !$q->execute() ) {
		var_dump($q->errorInfo());
	}
	
	echo '<hr/>Done!';
?>