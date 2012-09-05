<?php

	/**
	 * CLASSE Contatos
	 */
	class Reuse_ACK_Model_Contatos extends System_DB_Table
	{
		protected $_name = 'contatos';

		public function create(array $data) {
			
			if(isset($data['nome'])) {
				$data['remetente'] = $data['nome'];
				unset($data['nome']);
			}

			parent::create($data);			
		}

	}
?>