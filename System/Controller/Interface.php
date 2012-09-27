<?php
	interface System_Controller_Interface
	{
		/**
		 * carrega os métodos
		 * @param  [type] $obj             [description]
		 * @param  [type] $methodName      [description]
		 * @param  [type] $parameters=null [description]
		 * @return [type]                  [description]
		 */
		function load($obj,$methodName,$parameters=null);

		/**
		 * inicia a aplicacao
		 * @return [type] [description]
		 */
		function init();

		/**
		 * executado imediatamente antes da action
		 * @return [type] [description]
		 */
		function preDispatch();

		/**
		 * arquivos de controladores deve seguir o seguinte padrao:
		 * _funcaoAjax (para respostas ajax)
		 * _funcoesInternas 
		 * funcao (para funcioes normais)
		 */
		

	}
?>