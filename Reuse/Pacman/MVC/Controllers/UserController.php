<?php
	class Reuse_Pacman_MVC_Controllers_UserController extends Reuse_Base_Controller_UserController
	{
		/**
		 * url de retorno 
		 */
		const URL_RETURN = '/index/index';
		const AUTH_FILE = 'Pacman';

		/**
		 * inicializa a aplicacao
		 * @return [type] [description]
		 */
		public function preDispatch()
		{
			/**
			 * faz autenticação automática em todos os métodos
			 */
			$this->_authenticator->enableAuthentication();
			$this->_authenticator->setErrorPath(DEFAULT_ERROR_PATH);
			/**
			 * seta as funções que não necessitarão autenticação
			 */
			$this->_authenticator->setExceptions();

			$this->_auth = System_Auth::Factory(AUTH_FILE,null);

			/**
	    		 * adiciona o javascript de usuario
	    		 */
		  	$this->view->headScript()->appendFile($this->view->baseUrl().'/js/Pacman/user.js');

		}
	}

?>