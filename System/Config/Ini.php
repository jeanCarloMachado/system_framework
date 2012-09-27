<?php
	require_once "System/Config/Ini/Interface.php";
	require_once "System/DesignPattern/Factory/Interface.php";
	
	class System_Config_Ini extends Zend_Config_Ini implements System_Config_Ini_Interface,System_DesignPattern_Factory_Interface
	{
		private $_params;

		/**
		 * metodo estático que seta as configurações iniciais da aplicação
		 * @param [type] $params [description]
		 */
		public static function Factory($params=null)
		{

			$obj = new System_Config_Ini($params['file'],null);

			System_Config::register($obj);

			return $obj;
		}

		/**
		 * seta um array de parametros 
		 * @param [type] $params [description]
		 */
		public function setParams($params)
		{
			$this->_params = $params;
		}

		/**
		 * pega um parametro passado na configuração pela chave
		 * @param  [type] $key [description]
		 * @return [type]      [description]
		 */
		public function getParam($key)
		{
			return $this->_params[$key];
		}
	}
?>