<?php

	/**
	 * CLASSE Contatos
	 */
	class Reuse_ACK_Model_Contact extends System_DB_Table
	{
		protected $_name = 'contatos';

		public function create(array $set) {
			
			if(isset($set['nome'])) {
				$set['remetente'] = $set['nome'];
				unset($set['nome']);
			}

			return parent::create($set);			
		}

	}
?>