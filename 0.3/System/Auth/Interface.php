<?php

	interface System_Auth_Interface 
	{
		/**
		 * autentica um usuario
		 * @param  [type] $_login    [description]
		 * @param  [type] $_password [description]
		 * @return [type]            [description]
		 */
		function authenticate($_login,$_password);

		/**
		 * testa se existe um usuario
		 * @param  [type]  $_login    [description]
		 * @param  [type]  $_password [description]
		 * @return boolean            [description]
		 */
		public function hasUser($_login,$_password);

		/**
		 * seta o susuario
		 * @param [type] $array [description]
		 */
		public function setUser($array);

		/**
		 * retora o susuario
		 * @return [type] [description]
		 */
		public function getUser();

		/**
		 * testa se o usuario está autenticado
		 * @return boolean [description]
		 */
		public function isAuth();

		/**
		 * faz logoff do usuario
		 * @return [type] [description]
		 */
		public function logoff();
	}
?>