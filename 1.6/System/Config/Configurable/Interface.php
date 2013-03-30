<?php
	/**
	 * interface para as classes que podem acessar 
	 * arquivos de configuração
	 */
	interface System_Config_Configurable_Interface 
	{	
		/**
		 * retorna a configuração da classe 
		 * @param  [type] $config [description]
		 * @return [type]         [description]
		 */
		function getConfig();

		/**
		 * retorna as configurações globais
		 * @return [type] [description]
		 */
		function getConfigGlobal();
	}
?>