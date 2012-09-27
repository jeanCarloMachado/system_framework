<?php
	require_once "System/DesignPattern/Polymorphism/Interface.php";

	class System_Config implements System_DesignPattern_Polymorphism_Interface,System_Config_Interface
	{
		/**
		 * é passado argumentos a classe polimorfica
		 * a qual de acordo com esses argumentos intancia
		 * a classe necessária
		 * @param  array  $params [description]
		 * @return [type]         [description]
		 */
		public static function getInstance($params)
		{
			$file = new System_Object_File($params['file']);
			$type = $file->getType();

			$type[0] = strtoupper($type[0]);

			$className = "System_Config_".$type;

			$obj = $className::Factory($params);
		}

		public static final function register($config)
		{
			System_Registry::set("config",$config);
		}

		public static final function get()
		{
			$result = System_Registry::get("config");
			return $result;
		}
	}
?>