<?php
	class System_Config_Array implements System_Config_Interface
	{
		private static $info = array();
		const DEFAULT_KEY = "global";	

		public static function register($config,$key=self::DEFAULT_KEY)
		{
			self::$info[$key] = $config;
		}

		/**
		 * retorna a classe de configuração
		 * @return [type] [description]
		 */
		public static function get($key=self::DEFAULT_KEY)
		{
			return self::$info[$key];
		}
		
		/**
		 * retorna o nome do container global
		 * 
		 */
		public static function getContainerDefaultName()
		{
			return self::$info[self::DEFAULT_KEY]["containerGlobal"];
		}
	}
?>