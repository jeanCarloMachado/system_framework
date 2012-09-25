<?php

	/**
	 * CLASSE Contatos
	 */
	class Reuse_ACK_Model_Contatos extends System_DB_Table
	{
		protected $_name = 'contatos';

		public function create($array) {
			
			if(isset($array['nome'])) {
				$array['remetente'] = $array['nome'];
				unset($array['nome']);
			}

			return parent::create($array);			
		}

	}
?>