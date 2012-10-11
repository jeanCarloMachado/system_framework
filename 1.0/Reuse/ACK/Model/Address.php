<?php

	class Reuse_ACK_Model_Address extends Reuse_Base_Model_Address
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