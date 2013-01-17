<?php
	abstract class Reuse_ACK_Model_System extends System_Db_Table_Abstract
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