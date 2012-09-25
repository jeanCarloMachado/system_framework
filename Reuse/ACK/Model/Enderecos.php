<?php

	class Reuse_ACK_Model_Enderecos extends System_DB_Table
	{
		/**
		 * nome da tabela no banco de dados
		 * @var string
		 */
		protected $_name = 'enderecos';

		public function get($array,$params=null,$columns=null)
		{
			$array['status'] = 1;

			return parent::get($array,$params,$columns);
		}	

	}
?>