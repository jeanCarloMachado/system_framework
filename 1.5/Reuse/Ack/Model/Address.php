<?php

	abstract class Reuse_ACK_Model_Address extends System_Db_Table_Abstract
	{
		/**
		 * nome da tabela no banco de dados
		 * @var string
		 */
		protected $_name = 'enderecos';

		public function get(array $where,$params=null,$columns=null)
		{
			$where['status'] = 1;

			return parent::get($where,$params,$columns);
		}	

	}
?>