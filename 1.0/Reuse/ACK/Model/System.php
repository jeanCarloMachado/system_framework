<?php
	class Reuse_ACK_Model_System extends System_DB_Table
	{
		protected $_name = "sistema";


		public function getMaxItens() 
		{
			$result = $this->run("SELECT itens_pagina FROM $this->_name");

			$result = reset($result);

			return $result['itens_pagina'];
		}

	}
?>