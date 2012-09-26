<?php
	
	class Reuse_Auth_Estatistica extends System_Auth
	{	
		
		/**
		 * adiciona as configurações específicas
		 * @return [type] [description]
		 */
		public function customSettings()
		{
			$this->_tableName = "ec_user";
			$this->_identityColumn = "name";
			$this->_credentialColumn = "password";
		}
	}
?>