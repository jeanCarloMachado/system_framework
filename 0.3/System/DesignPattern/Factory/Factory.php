<?php
	/**
	 * interface para fazer requisicoes por soap
	 */
	require_once 'System/DesignPattern/Factory/Interface.php';
	require_once 'System/Plugins/Interface.php';


	class System_DesignPattern_Factory_Factory implements System_Plugins_Interface,System_DesignPattern_Factory_Interface {
		
		private $_params;

		public static function initialize($className,$params=null) {

			/**
			 * instancia uma classe filha
			 * @var ??
			 */
			$obj = new $className;
			/**
			 * seta os parametros para a requisicao
			 */
			$obj->setParams($params);

			/**
			 * retorna o objeto do tipo soap giulian
			 */
			return $obj;
		}

		/**
		 * metodo para ser sobreescrito pela filha
		 * @return [type] [description]
		 */
		public function run() {

		}

		/**
		 * metodo para ser sobreescrito pela classe filha
		 */
		public function setParams($params) {

			$this->_params = $params;
		}

		/**
		 * pega um parametro
		 * @param  [type] $param [description]
		 * @return [type]        [description]
		 */
		public function getParam($param) {

			return $this->_params[$param];
		}

	}
?>