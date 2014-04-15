<?php
	
	if( !class_exists('Utility') )
	{
		include(dirname(__FILE__) . '/class/Utility.php');
		$Utility = new Utility;
	}
	
	
	$db = $Utility->getPDO();
	
	$q = 
		"DROP TABLE lifeCategoryTree;";
		
	$q = $db->prepare($q);
	if(!$q->execute())
		var_dump($q->errorInfo());
	
	$q = 
		"CREATE TABLE lifeCategoryTree
		(
			id int not null auto_increment primary key
			, left_pointer int not null
			, right_pointer int not null
		);";
	
	$q = $db->prepare($q);
	if(!$q->execute())
		var_dump($q->errorInfo());
	
	$q = 
		"DROP TABLE lifeCategoryTreeLink;";
		
	$q = $db->prepare($q);
	if(!$q->execute())
		var_dump($q->errorInfo());
	
	$q = 
		"CREATE TABLE lifeCategoryTreeLink
		(
			id int not null auto_increment primary key
			, leaf_id int not null
			, leaf_name varchar(254) not null
			, link_type int not null default 1
			, link_id int not null
			, FOREIGN KEY (leaf_id) REFERENCES lifeCategoryTree(id)
		);";
		
	$q = $db->prepare($q);
	if(!$q->execute())
		var_dump($q->errorInfo());
	
	$q = 
		"DROP TABLE lifeNotes;";
		
	$q = $db->prepare($q);
	if(!$q->execute())
		var_dump($q->errorInfo());
		
		
	$q = 
		"CREATE TABLE lifeNotes
		(
			id int not null auto_increment primary key
			, note varchar(10000)
			, last_updated timestamp
		);";
		
	$q = $db->prepare($q);
	if(!$q->execute())
		var_dump($q->errorInfo());
		
	echo 'Done';
	
?>