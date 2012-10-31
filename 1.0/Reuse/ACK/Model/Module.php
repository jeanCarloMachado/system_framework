<?php

	class Reuse_ACK_Model_Module extends System_DB_Table
	{

		protected $_name = 'modulos';

		/**
		 * retorna o id do modulo
		 * @param  [type] $str [description]
		 * @return [type]      [description]
		 */
		public function getIdByName($str) 
		{
			$query = "SELECT id from $this->_name WHERE modulo='".$str."'";

			$result = $this->run($query);

			return $result[0]['id'];
		}
	}
?>