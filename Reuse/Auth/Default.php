<?php
	
	class Reuse_Auth_Default extends System_Auth
	{	
		/**
		 * adiciona as configurações específicas
		 * @return [type] [description]
		 */
		public function customSettings()
		{

			$this->_userTableModel = "Usuario";
			$this->_identityColumn = "desp_cod";
			$this->_credentialColumn = "senha_responsavel";
		}
	}
?>