<?php
	
	class Reuse_Auth_Pacman extends System_Auth
	{	
		
		/**
		 * adiciona as configurações específicas
		 * @return [type] [description]
		 */
		public function customSettings()
		{
			$this->_userTableModel = "Reuse_Pacman_MVC_Models_User";
			$this->_identityColumn = "login";
			$this->_credentialColumn = "password";
		}
	}
?>