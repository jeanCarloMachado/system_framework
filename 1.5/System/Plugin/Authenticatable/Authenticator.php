<?php
	/**
	 * nao está implementando as interface (ajustar)
	 */

	class System_Plugin_Authenticatable_Authenticator 
	{
		/**
		 * responsável pela autenticação automática do sistema
		 * @var Auth
		 */
		protected $_authenticator = null;


		/**
		 * true quando o serviço de autenticação está ativado
		 * @var boolean
		 */
		protected $_beingAuthenticated = false;
		/**
		 * lista de métodos que não serão autenticados pelo servico
		 * @var boolean
		 */
		protected $_exceptions = array();

		/**
		 * view a carregar caso nao esteja autenticado
		 * @var [type]
		 */
		protected $_errorPath = null;


		/**
		 * retorna o objeto de autenticação do controlador
		 * @return Auth [description]
		 */


		public function getAuthenticator()
		{
			if(!isset($this->_authenticator))
				$this->_authenticator = System_Auth::Factory('Default',null);

		    return $this->_authenticator;
		}
		
		public function setAuthenticator($authenticator)
		{
		    $this->_authenticator = $authenticator;
		}
			

		public function enableAuthentication()
		{
			$this->_beingAuthenticated = true;
		}

		public function disableAuthentication()
		{
			$this->_beingAuthenticated = false;
		}

		public function setExceptions($array)
		{
			foreach($array as $element) {
				array_push($this->_exceptions,$element);
			}

			$this->_exceptions = $array;
		}

		public function getExceptions()
		{
			return $this->_exceptions;
		}

		public function authenticationIsEnabled()
		{
			if($this->_beingAuthenticated)
				return true;

			return false;
		}

		public function getErrorPath()
		{
			return $this->_errorPath;
		}	

		public function setErrorPath($path)
		{
			$this->_errorPath = $path;
		}
	
		
		
		public function getAjaxName()
		{
		    return $this->ajaxName;
		}
		
		public function setAjaxName($ajaxName)
		{
		    $this->ajaxName = $ajaxName;
		}

		public function testAuthentication($methodName)
		{	

			if($this->authenticationIsEnabled()) {

				$auth = $this->getAuthenticator();	

				if((!$auth->isAuth()))
				{		/**
					 * testa se o método está no array
					 */
					$this->_authenticator = System_Auth::Factory('Video',null);
					

					if(!in_array($methodName,$this->getExceptions()))
					{
						$_errorPath = $this->getErrorPath();
						if(!isset($_errorPath))
						{
							throw new Exception('Método não autenticado e nenhum caminho passado para error.');
						}
						header("Location: $_errorPath");
						die;
					}

					return;
				}
			}
		}

	}

?>