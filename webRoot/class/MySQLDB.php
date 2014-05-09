<?php
	
	if( !class_exists('Utility') ) {
		include(dirname(__FILE__) . '/Utility.php');
		$Utility = new Utility;
	}
	
	class MySQLDB extends Utility {
		
		public function addNote($noteName, $leftPointer, $userId) {
			$PDODB = $this->getPDO();
			
			$q = $PDODB->prepare("INSERT INTO notes
								  (
									title
								  )
								  VALUES
								  (
									:title
								  );");
			$q->bindParam(':title', $noteName);
	
			if( !$q->execute() ) {
				$this->throwError($q->errorInfo());
				return false;
			}
			
			$noteId = $PDODB->lastInsertId();
			
			$q = $PDODB->prepare("UPDATE category_tree
								  SET left_pointer = left_pointer + 2
								  WHERE left_pointer > :left_pointer
								    AND user_id = :userId;");
			$q->bindParam(':left_pointer', $leftPointer);
			$q->bindParam(':userId', $userId);
	
			if( !$q->execute() ) {
				$this->throwError($q->errorInfo());
				return false;
			}
			
			$q = $PDODB->prepare("UPDATE category_tree
								  SET right_pointer = right_pointer + 2
								  WHERE right_pointer > :left_pointer
								    AND user_id = :userId;");
			$q->bindParam(':left_pointer', $leftPointer);
			$q->bindParam(':userId', $userId);
	
			if( !$q->execute() ) {
				$this->throwError($q->errorInfo());
				return false;
			}
			
			$left_pointer = $leftPointer+1;
			$right_pointer = $left_pointer+1;
			
			
			$q = $PDODB->prepare("INSERT INTO category_tree
								  (
									user_id
									, note_id
									, left_pointer
									, right_pointer
								  )
								  VALUES
								  (
									:user_id
									, :note_id
									, :left_pointer
									, :right_pointer
								  );");
			$q->bindParam(':user_id', $userId);
			$q->bindParam(':note_id', $noteId);
			$q->bindParam(':left_pointer', $left_pointer);
			$q->bindParam(':right_pointer', $right_pointer);
	
			if( !$q->execute() ) {
				$this->throwError($q->errorInfo());
				return false;
			}
			
			return true;
		}
		
		public function getUserInfo($userId) {
			$PDODB = $this->getPDO();
			
			$q = $PDODB->prepare("SELECT id
										 , email
										 , username
										 , name
								  FROM users
								  WHERE id = :id;");
			$q->bindParam(':id', $userId);
	
			if( !$q->execute() ) {
				$this->throwError($q->errorInfo());
				return false;
			}
			
			$q = $q->fetchAll();
			return $q[0];
		}
		
		public function getNote($noteId) {
			$PDODB = $this->getPDO();
			
			$q = $q->prepare("SELECT id
									 , title
									 , note
							  FROM notes
							  WHERE id = :id;");
			$q->bindParam(':id', $noteId);
			
			if( !$q->execute() ) {
				$this->throwError($q->errorInfo());
				return false;
			}
			
			$q = $q->fetchAll();
			
			if( count($q) == 0 )
				return array();
			
			return $q[0];
		}
		
		public function getNoteCategory($userId, $leftPointer) {
			$PDODB = $this->getPDO();
			
			$q = $PDODB->prepare("SELECT CTO.id
										 , CTO.note_id
										 , NTE.title
										 , CTO.left_pointer
										 , CTO.right_pointer
								  FROM category_tree CTO
								  INNER JOIN category_tree CTS
									ON	CTS.left_pointer = :left_pointer
									AND CTS.user_id = :user_id
									AND CTO.user_id = CTS.user_id
									AND CTS.left_pointer < CTO.left_pointer
									AND CTS.right_pointer > CTO.right_pointer
								  INNER JOIN notes NTE
									ON NTE.id = CTO.note_id
								  ORDER BY CTO.left_pointer;");
			$q->bindParam(':left_pointer', $leftPointer);
			$q->bindParam(':user_id', $userId);
	
			if( !$q->execute() ) {
				$this->throwError($q->errorInfo());
				return false;
			}
			
			return $q->fetchAll();
		}
		
		public function registerUser($email, $password, $username, $name) {
			$PDODB = $this->getPDO();
			
			$q = $PDODB->prepare("SELECT id
								  FROM users
								  WHERE email = :email;");
			$q->bindParam(':email', $email);
			if( !$q->execute() ) {
				$this->throwError($q->errorInfo());
				return false;
			}
			
			if( count($q->fetchAll()) == 0 ) {
				$q = $PDODB->prepare("INSERT INTO users
									  (
										email
										, password
										, username
										, name
									  )
									  VALUES
									  (
										:email
										, :password
										, :username
										, :name
									  );");
				$q->bindParam(':email', $email);
				$q->bindParam(':password', $password);
				$q->bindParam(':username', $username);
				$q->bindParam(':name', $name);
				
				if( !$q->execute() ) {
					$this->throwError($q->errorInfo());
					return false;
				}
				
				$user_id = $PDODB->lastInsertId();
				
				// Finally, prep category tree				
				$q = $PDODB->prepare("INSERT INTO category_tree
									  (
										user_id
										, left_pointer
										, right_pointer
									  )
									  VALUES
									  (
										:user_id
										, 0
										, 1
									  );");
				$q->bindParam(':user_id', $user_id);
				
				if( !$q->execute() ) {
					$this->throwError($q->errorInfo());
					return false;
				}
				
				return true;
			}
			
			return false;
		}
		
		public function verifyLogin($email, $password) {
			$PDODB = $this->getPDO();
			
			$q = $PDODB->prepare("SELECT id
								  FROM users
								  WHERE email = :email
									AND password = :password;");
			$q->bindParam(':email', $email);
			$q->bindParam(':password', $password);
	
			if( !$q->execute() ) {
				$this->throwError($q->errorInfo());
				return false;
			}
			
			$q = $q->fetchAll();
			
			if( count($q) ) {
				return $q[0]['id'];
			}
			
			return false;
		}
	}
	
?>