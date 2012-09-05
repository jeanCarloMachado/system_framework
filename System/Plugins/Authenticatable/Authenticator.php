<?php
	class System_Plugins_Authenticatable_Authenticator implements System_Plugins_Authenticatable_Interface,System_Plugins_Interface
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
		protected $_exceptions = null;

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
			$this->exceptions = $array;
		}

		public function getExceptions()
		{
			return $this->exceptions;
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
			$this->_authenticator = System_Auth::Factory('Default',null);
			$this->setAuthenticator();
			$auth = $this->getAuthenticator();		

			if($this->authenticationIsEnabled() && (!$auth->isAuth()))
			{		/**
				 * testa se o método está no array
				 */
				if(!in_array($methodName,$this->getExceptions()))
				{
					$errorPath = $this->getErrorPath();
					if(!isset($errorPath))
					{
						throw new Exception('Método não autenticado e nenhum caminho passado para error.');
					}

					header("Location: ".$errorPath."");
				}

				return;
			}
		}

	}

?>