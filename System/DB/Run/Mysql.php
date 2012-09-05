<?php

	class System_DB_Run_Mysql extends System_DesignPattern_Factory_Factory implements System_DB_SQL_Interface {

		public function  run() {
			
			$db = $this->getParam('db');

			try {
				$mysql = $db->prepare($this->getParam('query'));
				$mysql->execute();
				return $mysql->fetchAll();
			} catch (Exception $e) 	{
				return false;
			}
			return $result;		
		}
	}

?>