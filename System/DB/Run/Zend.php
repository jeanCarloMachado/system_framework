<?php

	class System_DB_Run_Zend extends System_DesignPattern_Factory_Factory implements System_DB_SQL_Interface {

	
		public function  run() {
			
			$db = $this->getParam('db');

			try {
					$stmt = $db->query($this->getParam('query'));
					return $stmt->fetchAll();

			} catch (Exception $e) {

					return false;
				
			}
		}
			
	}

?>