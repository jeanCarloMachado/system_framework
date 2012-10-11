<?php
	
	/**
	 * interface para fronts de soap
	 */
	interface System_Soap_Client_Interface
	{
		/**
		 * faz a requisicao
		 * @return [type] [description]
		 */
		function makeRequest();

		/**
		 * seta os parametros da requisicao
		 * @param [type] $params [description]
		 */
		function setParams($params);

		function getParam($name);

	}
?>