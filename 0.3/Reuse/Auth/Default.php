<?php
	
	class Reuse_Auth_Default extends System_Auth
	{	
		
		/**
		 * adiciona as configurações específicas
		 * @return [type] [description]
		 */
		public function customSettings()
		{
			session_start();

			$this->_tableName = "usuarios_ikro";
			$this->_identityColumn = "desp_cod";
			$this->_credentialColumn = "senha_responsavel";
		}
	}
?>