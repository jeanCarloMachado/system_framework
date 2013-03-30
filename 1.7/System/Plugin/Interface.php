<?php
	/**
	 * interface padrão para todos os plugins do sistema
	 */
	interface System_Plugin_Interface
	{	
		/**
		 * habilita o funcionamento do plugin
		 * @return [type] [description]
		 */
		function enable();

		/**
		 * clausula que pode definir se um plugin está habilitado ou naõ
		 * dinamicamente
		 * @return [type] [description]
		 */
		protected function _autoEnable();

		/**
		 * deshabilita o funcionamento do plugin
		 * @return [type] [description]
		 */
		function disable();

		/**
		 * testa se o plugin está habilitado
		 * @return boolean [description]
		 */
		function isEnabled();

		/**
		 * cham a funcao chamada por funcName
		 * @param  str    $funcName [description]
		 * @return [type]           [description]
		 */
		public function load(array $params);
		
		/**
		 * função que deve ser implementada pelo usuário
		 * faz o plugin funcionar propriamente dito
		 * @param  array  $params [description]
		 * @return [type]         [description]
		 */
		protected function run(array $params);

		
	}
?>