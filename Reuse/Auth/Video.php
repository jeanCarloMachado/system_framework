<?php
	
	class Reuse_Auth_Video extends System_Auth
	{	
		
		/**
		 * adiciona as configurações específicas
		 * @return [type] [description]
		 */
		public function customSettings()
		{
			$this->_tableName = "users";
			$this->_identityColumn = "login";
			$this->_credentialColumn = "password";
		}
	}
?>