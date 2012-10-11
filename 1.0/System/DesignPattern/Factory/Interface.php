<?php
	interface System_DesignPattern_Factory_Interface {

		/**
		 * metodo estático que seta as configurações iniciais da aplicação
		 * @param [type] $params [description]
		 */
		public static function Factory($params=null);

		/**
		 * seta um array de parametros 
		 * @param [type] $params [description]
		 */
		public function setParams($params);

		/**
		 * pega um parametro passado na configuração pela chave
		 * @param  [type] $key [description]
		 * @return [type]      [description]
		 */
		public function getParam($key);
	}
?>