<?php

if( !class_exists('Note') ) {
	include(dirname(__FILE__) . '/../model/Note.php');
	$Note = new Note;
}

class NoteController extends Note {
	
	function __construct($userId, $noteId = null) {
		if( $noteId == null ) {
			
		}
	}
	
	public function getNoteCategory($userId, $leftPointer) {
		$DB = Utility::getDB();
		
		$children = $DB->getNoteCategory($userId, $leftPointer);
		
		return $this->pruneChildren($children);
	}
	
	public function addNote($note, $leftPointer, $userId) {
		$DB = Utility::getDB();
		
		return $DB->addNote($note, $leftPointer, $userId);
		
	}
	
	public function getNote($noteId) {
		$DB = $this->getDB();
		return $DB->getNote($noteId);
	}
	
	public function pruneChildren($query) {
		if( count($query) == 0 )
			return $query;
			
		$finalResults = array();
		$finalResults[] = $query[0];
		for($i = 1; $i < count($query); $i++) {
			if( $query[$i]['left_pointer'] > $finalResults[count($finalResults)-1]['right_pointer'] )
				$finalResults[] = $query[$i];
		}
		
		return $finalResults;
	}
	
}

?>