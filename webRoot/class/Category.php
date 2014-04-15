<?php
	
	if( !class_exists('Utility') )
	{
		include(dirname(__FILE__) . '/Utility.php');
		$Utility = new Utility;
	}
	
	class Category extends Utility {
		
		public function getLifeCategories($category_id = 0) {
			$PDODB = Utility::getPDO();
			
			$thisQuery = $PDODB->prepare("SELECT LCT.id AS category_id
												 , CTL.leaf_name
												 , LCT.left_pointer
												 , LCT.right_pointer
										  FROM lifeCategoryTree LCT
										  INNER JOIN lifeCategoryTreeLink CTL
											ON LCT.id = CTL.leaf_id
										  INNER JOIN lifeCategoryTree CTS
											" . (($category_id == 0) ? 'ON' : 'ON CTS.id = :category_id AND') . " 
											 LCT.left_pointer > CTS.left_pointer
											AND LCT.right_pointer < CTS.right_pointer
										  ORDER BY LCT.left_pointer;");
			if($category_id != 0) {
				$thisQuery->bindParam(':category_id', $category_id);
			}
			if(!$thisQuery->execute()) {
				var_dump($thisQuery->errorInfo());
				return array();
			}
			
			$qryChildren = $thisQuery->fetchAll();
			
			for($i = 1; $i < count($qryChildren); $i++) {
				if(	($qryChildren[$i]['left_pointer'] > $qryChildren[$i-1]['left_pointer']
					&& $qryChildren[$i]['left_pointer'] < $qryChildren[$i-1]['right_pointer']) )
				{
					$qryChildren = $this->deleteArrayIndex($qryChildren, $i);
					$i--;
				}
			}
			
			return $qryChildren;
		}
		
		public function getLifeNote($category_id = 0) {
			$PDODB = Utility::getPDO();
			
			$thisQuery = $PDODB->prepare("SELECT LN.note
												 , LN.last_updated
										  FROM lifeCategoryTreeLink CTL
										  INNER JOIN lifeNotes LN
											ON CTL.link_id = LN.id;");
			$thisQuery->bindParam(':category_id', $category_id);
			$thisQuery->execute();
			$qryReturn = $thisQuery->fetchAll();
			
			return $qryReturn[0];
		}
		
		public function deleteArrayIndex($arr, $index) {
			$arrReturn = array();
			for($i = 0; $i < count($arr); $i++) {
				if( $i != $index )
					$arrReturn[] = $arr[$i];
			}
			return $arrReturn;
		}
		
		public function blindGrab() {
			$PDODB = Utility::getPDO();
			
			$thisQuery = $PDODB->prepare("SELECT *
										  FROM lifeCategoryTree;");
			if(!$thisQuery->execute()) {
				var_dump($thisQuery->errorInfo());
				return array();
			}
			
			return $thisQuery->fetchAll();
		}
		
		public function addNote($left_pointer, $leaf_name, $note) {
			$PDODB = Utility::getPDO();
			
			$thisQuery = $PDODB->prepare("INSERT INTO lifeNotes
										  (
											note
										  )
										  VALUES
										  (
											:note
										  );");
			$thisQuery->bindParam(':note', $note);
			if(!$thisQuery->execute()) {
				var_dump($thisQuery->errorInfo());
				return false;
			}
			
			$noteId = $PDODB->lastInsertId();
			
			return $this->addCategory($left_pointer, $leaf_name, 1, $noteId);
		}
		
		public function addCategory($left_pointer, $leaf_name, $link_type, $link_id) {
			$PDODB = Utility::getPDO();
			
			// Make space for the new category
			$thisQuery = $PDODB->prepare("UPDATE lifeCategoryTree
										  SET left_pointer = (left_pointer + 2)
										  WHERE left_pointer >= :left_pointer;");
			$thisQuery->bindParam(':left_pointer', $left_pointer);
			if(!$thisQuery->execute()) {
				var_dump($thisQuery->errorInfo());
				return false;
			}
						
			$thisQuery = $PDODB->prepare("UPDATE lifeCategoryTree
										  SET right_pointer = (right_pointer + 2)
										  WHERE right_pointer >= :left_pointer;");
			$thisQuery->bindParam(':left_pointer', $left_pointer);
			if(!$thisQuery->execute()) {
				var_dump($thisQuery->errorInfo());
				return false;
			}
			
			$thisQuery = $PDODB->prepare("INSERT INTO lifeCategoryTree
										  (
											left_pointer
											, right_pointer
										  )
										  VALUES
										  (
											:left_pointer
											, :right_pointer
										  );");
			$thisQuery->bindParam(':left_pointer', $left_pointer);
			$right_pointer = ($left_pointer+1);
			$thisQuery->bindParam(':right_pointer', $right_pointer);
			if(!$thisQuery->execute()) {
				var_dump($thisQuery->errorInfo());
				return false;
			}
			
			// Get the leaf ID
			$leaf_id = $PDODB->lastInsertId();
			
			$thisQuery = $PDODB->prepare("INSERT INTO lifeCategoryTreeLink
										  (
											leaf_id
											, leaf_name
											, link_type
											, link_id
										  )
										  VALUES
										  (
											:leaf_id
											, :leaf_name
											, :link_type
											, :link_id
										  );");
			$thisQuery->bindParam(':leaf_id', $leaf_id);
			$thisQuery->bindParam(':leaf_name', $leaf_name);
			$thisQuery->bindParam(':link_type', $link_type);
			$thisQuery->bindParam(':link_id', $link_id);
			if(!$thisQuery->execute()) {
				var_dump($thisQuery->errorInfo());
				return false;
			}
			
			return true;
		}
		
		
	}
	
?>