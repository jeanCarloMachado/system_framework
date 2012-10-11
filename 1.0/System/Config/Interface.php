<?php
	interface System_Config_Interface 
	{
		static function register($config);

		/**
		 * retorna a classe de configuração
		 * @return [type] [description]
		 */
		static function get();
	}
?>