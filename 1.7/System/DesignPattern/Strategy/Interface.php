<?php

	/**
	 * interface para classes Strategy
	 */
	interface System_DesignPattern_Strategy_Interface 
	{
		/**
		 * inicializa a classe pai e retorna o objeto adequado de acordo com
		 * className
		 * @param [type] $className  [description]
		 * @param [type] $parameters [description]
		 */
		 static public function Factory($className,$parameters=null);

		/**
		 * seta os parametros no atributo local da classe
		 * @param [type] $params [description]
		 */
		function setParams($params);


		/**
		 * pega um atributo o qual foi setado
		 * @param  [type] $param [description]
		 * @return [type]        [description]
		 */
		function getParam($param);

		/**
		 * seta as configurações específicas de cada filho
		 * @return [type] [description]
		 */
		function customSettings();
	}
?>