<?php
	/**
	 * interface para as classes que podem acessar 
	 * arquivos de configuração
	 */
	abstract class System_Config_Configurable_Abstract
	{	
		/**
		 * retorna a configuração da classe 
		 * @param  [type] $config [description]
		 * @return [type]         [description]
		 */
		abstract function getConfig();
		/**
		 * retorna as configurações globais
		 * @param  [type] $config [description]
		 * @return [type]         [description]
		 */
		function getConfigGlobal() 
		{
			$config = System_Config::get();
			
			return $config->global;
		}
	}
?>

		