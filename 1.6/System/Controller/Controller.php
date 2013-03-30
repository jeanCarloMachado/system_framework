<?php
	abstract class System_Controller /* extends Zend_Controller_Action */ implements System_Controller_Interface,System_Config_Configurable_Interface
	{
		/**
		 *  módulo do ack
		 * @var int
		 */
		protected $_module;
		
		/**
		 * variavel que guarda as informações recebidas por json
		 * @var [type]
		 */
		protected $ajax;
		/**
		 * nome da variavel que recebe o json
		 * @var [type]
		 */
		protected $_ajaxName;
		protected $_frontAjaxName;
		
		/**
		 * plugin responsável pela autenticação automática do sistema
		 * @var System_Plugins_Athenticatable_Authenticator
		 */
		protected $_authenticator = null;
		
		/**
		 * estado do plugin de controle de acesso
		 * @var unknown
		 */
		protected $acessNumberStatus = true;
		/**
		 *	recebe o valor do módulo e o seta.
		 *
		 * @param int $module
		 */
		protected $actionName = null;
		protected $parameters = null;
		
		protected $view;
		
		public function __construct()
		{
			$this->init();
			
		}
		/**
		 * adiciona uma imagem ao lightbox do usuario logado
		 */
		public function routerAjax()
		{
			$action = ($this->ajax["action"]) ? $this->ajax["action"] : $this->ajax["acao"];
			$action.= "Ajax";
			$this->$action();
			die;
		}
		
		
		public function getAcessNumberStatus()
	    {
	        return $this->acessNumberStatus;
	    }
	    
	    public function setAcessNumberStatus($acessNumberStatus)
	    {
	        $this->acessNumberStatus = $acessNumberStatus;
	        return $this;
	    }
		
		/**
		 * retorna a configuração da classe
		 * @param  [type] $config [description]
		 * @return [type]         [description]
		 */
		public function getConfig()
		{
			$config = System_Config::get();
			return $config->system->controller;
		}
	
		/**
		 * retorna as configurações globais
		 * @param  [type] $config [description]
		 * @return [type]         [description]
		 */
		public function getConfigGlobal()
		{
			$config = System_Config::get();
				
			return $config->global;
		}
	
		/**
		 * carrega o método automaticamente
		 * @param  [type] $class      [description]
		 * @param  [type] $methodName [description]
		 * @param  [type] $parameters [description]
		 * @return [type]             [description]
		 */
		public function load($class,$methodName,$parameters=null)
		{
			$this->actionName = $methodName;
			$this->parameters = $parameters;
			/**
			 * executa o pre-dispatch com as config. de autenticação
			 */
			$this->preDispatch();
			/**
			 * testa a autenticação
			*/
			$this->_authenticator->testAuthentication($methodName);
			
			if($this->getAcessNumberStatus()) {
				System_Plugin_AccessNumber::increment();
			}
	
			$result = $class->$methodName($parameters);
			
			$this->view->render($result);		
			
			return $result;
		}
	
		public function init()
		{
			$this->view = System_View::getInstance();
			$this->view->setControllerName(System_FrontController::getControllerName());
			$this->view->setViewName(System_FrontController::getViewName());
			
			/**
			 * instancia o arquivo de configuração do sistema
			 */
			$configGlobal = $this->getConfigGlobal();
			/** seta o nome dos pacotes json */
			$this->_ajaxName = $configGlobal->jsonPackageName;
			$this->_frontAjaxName = $configGlobal->jsonFrontPackageName;
			/**
			 * se o módulo foi passado no construtor então o seta (esse modo é deprecated)
			 */
			if(isset($module))
				$this->setModule($module);

			/**
			 * Le os dados passados por json
			 * @var [type]
			*/
			//dg(System_Object_Json::read($_POST[$this->_ajaxName]));
			if(!empty($_POST[$this->_ajaxName]))
				$this->ajax = System_Object_Json::read($_POST[$this->_ajaxName]);
			else if(!empty($_POST[$this->_frontAjaxName]))
				$this->ajax = 	System_Object_Json::read($_POST[$this->_frontAjaxName]);
			else 
				$this->ajax = System_Object_Json::read($_POST);
			
			

			$this->_authenticator = new System_Plugin_Authenticatable_Authenticator;
			$this->_accessNumber = new System_Plugin_AccessNumber;
		}
		 
		public function getModule()
		{
			return $this->_module;
		}

		public function setModule($module)
		{
			$this->_module = $module;
		}
		/**
		 * Pre-dispatch routines
		 *
		 * Called before action method. If using class with
		 * {@link Zend_Controller_Front}, it may modify the
		 * {@link $_request Request object} and reset its dispatched flag in order
		 * to skip processing the current action.
		 *
		 * @return void
		 */
		public function preDispatch()
		{
		}
	}
?>