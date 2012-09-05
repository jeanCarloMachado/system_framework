<?php
	class System_DB_Table_Row {

		
		private static $_tableName;
		/**
		 * dados da linha
		 * @var array
		 */
		private $_data = null;

		/**
		 * nomes das colunas das funções
		 * @var [type]
		 */
		private static $_header;
	
		//----------------GETTERS & SETTERS ----------------
		public function getRow() {
		    return $this->_data;
		}
		
		public function setRow($headers,$columns) {

			$obj = new System_DB_Table_Row;

			$rowArray =array();
			foreach($headers as $headerId => $header) {
				$rowArray[$header] = isset($columns[$headerId]) ? $columns[$headerId] : '';
			}

			$obj->_data = $rowArray;
			$obj->_header = $headers;

			return $obj;
		}


		public function getColumn($colunmName) {

			if(array_key_exists($colunmName, $this->_data))
		    	return $this->_data[$colunmName];

		    return false;
		}
		
		public function setColumn($name,$value) {

		    $this->_data[$name] = $value;
		}
		

		public function getTableName()
		{
		    return $this->_tableName;
		}
		
		public function setTableName($tableName)
		{
		    $this->_tableName = $tableName;
		}

		public function getHeader()
		{
		    return $this->_header;
		}
		
		public function setHeader($header)
		{
		    $this->_header = $header;
		}
		
		
		//----------------FIM GETTERS & SETTERS ----------------
	}
?>