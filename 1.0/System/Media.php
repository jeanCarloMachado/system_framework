<?php
	require_once "System/DesignPattern/Polymorphism/Interface.php";

	class System_Media implements System_DesignPattern_Polymorphism_Interface
	{
		/**
		 * é passado argumentos a classe polimorfica
		 * a qual de acordo com esses argumentos intancia
		 * a classe necessária
		 * @param  array  $params [description]
		 * @return [type]         [description]
		 */
		public static function getInstance(array $params)
		{
			
		}
	}
?>