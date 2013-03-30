<?php
	require_once "System/DesignPattern/Polymorphism/Interface.php";
	require_once "System/Config/Interface.php";
	require_once "System/Config/Ini.php";
	require_once "System/Registry.php";
	require_once "System/Object/Password.php";
	require_once "System/Object/File/File.php";

	/**
	 * utilizando:
	 *   $config = System_Config::get();
	 *
     *   dg($config->app->prefix);
	 */
	class System_Config implements System_DesignPattern_Polymorphism_Interface,System_Config_Interface
	{
		CONST CONFIG_FILE_LABEL = "config";

		private $_password;

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

			$obj = call_user_func(array($className,'Factory'),$params);

			return $obj;
		}

		public static final function register($config)
		{
			System_Registry::set(System_Object_Password::encrypt(self::CONFIG_FILE_LABEL),$config);
		}

		public static final function get()
		{
			$result = System_Registry::get(System_Object_Password::encrypt(self::CONFIG_FILE_LABEL));
			return $result;
		}
	}
?>