<?php
	abstract class System_Media_Abstract implements System_Media_Interface,System_DesignPattern_Factory_Interface
	{
		private $_params = array();

		/**
		 * metodo estático que seta as configurações iniciais da aplicação
		 * @param [type] $params [description]
		 */
		public static function Factory(array $params)
		{

		}

		/**
		 * seta um array de parametros 
		 * @param [type] $params [description]
		 */
		public function setParams(array $params)
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