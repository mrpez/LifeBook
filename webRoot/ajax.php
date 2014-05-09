<?php

	if( !class_exists('LoginController') ) {
		include(dirname(__FILE__) . '/class/controller/LoginController.php');
		$LoginController = new LoginController;
	}
	
	if( !class_exists('UserController') ) {
		include(dirname(__FILE__) . '/class/controller/UserController.php');
		$UserController = new UserController($LoginController->getUserId());
	}
	
	if( !array_key_exists('method', $_POST) ) {
		$_POST['method'] = '';
	}
	
	switch($_POST['method']) {
		case 'getBaseTree':{
			if( !class_exists('NoteController') ) {
				include(dirname(__FILE__) . '/class/controller/NoteController.php');
				$NoteController = new NoteController($LoginController->getUserId());
			}
			$ret = array();
			$ret['notes'] = $NoteController->getNoteCategory($LoginController->getUserId(), 0);
			$ret['returnStatus'] = 0;
			$ret['returnString'] = '';
			echo json_encode($ret);
			break;
		}
		
		case 'createNote':{
			if( !class_exists('NoteController') ) {
				include(dirname(__FILE__) . '/class/controller/NoteController.php');
				$NoteController = new NoteController($LoginController->getUserId());
			}
			$ret = array();
			$ret['notes'] = $NoteController->addNote($_POST['noteName'], 0, $LoginController->getUserId());
			$ret['returnStatus'] = 0;
			$ret['returnString'] = '';
			echo json_encode($ret);
			break;
		}
		
		case 'getNote':{
			if( !class_exists('NoteController') ) {
				include(dirname(__FILE__) . '/class/controller/NoteController.php');
				$NoteController = new NoteController($LoginController->getUserId());
			}
			$ret = array();
			$ret['note'] = $NoteController->getNote($_POST['noteId']);
			$ret['returnStatus'] = 0;
			$ret['returnString'] = '';
			echo json_encode($ret);
			break;
		}
	}
	
?>