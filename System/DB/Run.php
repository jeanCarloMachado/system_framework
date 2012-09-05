<?php

	class System_DB_Run implements System_DB_SQL_Interface {

		private $_db;
		
		public function run($query,$db,$type) {

			$this->_db = $db;
			/**
			 * tenta o banco do ack
			 */
			if($type == 'normal') {

				$result = $this->runGlobal($query);
			} else if($type == 'zf') {

				$result = $this->runZF($query);				
			}

			return $result;
		}

		/**
		 * executa a aplicação como zend framework
		 * @return [type] [description]
		 */
		private function runZF($query) {

			$stmt = $this->_db->query($query);
			$result = $stmt->fetchAll();

			return $result;
		}

		/**
		 * executa a aplicação como ack
		 * @return [type] [description]
		 */
		private function runGlobal($query) {

			try
			{
				$mysql = $this->_db->prepare($this->query);
				$mysql->execute();
				return $mysql->fetchAll();
			} 
			catch (Exception $e)
			{
				return false;
			}
		}
			
	}

?>