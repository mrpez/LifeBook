<?php
	
	if( !class_exists('Utility') )
	{
		include(dirname(__FILE__) . '/class/Utility.php');
		$Utility = new Utility;
	}
	
	if( !array_key_exists('method', $_POST) )
		$_POST['method'] = null;
		
	switch($_POST['method']) {
		
		case 'getCategory':
			if( array_key_exists('category_id', $_POST) ) {
				if( !class_exists('Category') )
				{
					include(dirname(__FILE__) . '/class/Category.php');
					$Category = new Category;
				}
				
				$arrReturn['children'] = $Category->getLifeCategories($_POST['category_id']);
				$arrReturn['note'] = $Category->getLifeNote($_POST['category_id']);
				
				echo json_encode($arrReturn);
				break;
			}
	
		default:
			header('HTTP/1.1 400 Bad Request', true, 400);
			die;
	}
	
	
?>