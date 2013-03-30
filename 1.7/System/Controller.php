<?php
	abstract class System_Controller implements System_Controller_Interface
	{
		protected $_module;
		protected $ajax;
		protected $_ajaxName;
		protected $_frontAjaxName;
		protected $_authenticator = null;
		protected $acessNumberStatus = true;
		protected $actionName = null;
		protected $parameters = null;
		protected $view;
		protected $container;
		

		/**
		 * Acao que sera executada pelo router
		 * @var method
		 */
		const ACTION_KEY = "action";
		
		/**
		 * chave do array contendo os dados a serem manipulados
		 * @var string
		 */
		const DATA_KEY = "data";
		
		/**
		 * chave do array contendo os paramentros que devinem o que será feito com os dados
		 * @var string
		 */
		const PARAMETERS_KEY = "parameters";

		

		public function __construct()
		{
			$this->init();
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
			$this->init();
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
			
			$container  = System_Registry::get("container");
			$path = $container->getLayoutPath();
			
			
			$this->view->setLayoutPath($path);
			$path = $container->getViewPath();
			$this->view->setViewPath($path);
			$this->view->render($result);		
			
			return $result;
		}
	
		public function init()
		{
			$this->view = System_View::getInstance();
			$this->view->setControllerInstance($this);
			$this->view->setViewName(System_FrontController::getViewName());
			
			$container  = System_Registry::get("container");
			$jsonInfo = $container->getJsonInfo();
			
			/** seta o nome dos pacotes json */
			$this->_ajaxName = $jsonInfo["packageName"];
			$this->_frontAjaxName = $jsonInfo["frontPackageName"];
			/**
			 * se o módulo foi passado no construtor então o seta (esse modo é deprecated)
			 */
			if(isset($module))
				$this->setModule($module);

			/**
			 * Le os dados passados por json
			 * @var [type]
			*/
			if(!empty($_POST[$this->_ajaxName]))
				$this->ajax = System_Object_Json::read($_POST[$this->_ajaxName]);
			else if(!empty($_POST[$this->_frontAjaxName]))
				$this->ajax = 	System_Object_Json::read($_POST[$this->_frontAjaxName]);
			else if(!empty($_POST))
				$this->ajax = System_Object_Json::read($_POST);
			
			$this->_authenticator = new System_Plugin_Authenticatable_Authenticator;
			$this->_accessNumber = $container->getAcessNumberPluginInstance();
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
		public function router()
		{
			$acao = $this->getAjaxAction() . "Ajax";
			
			if(method_exists($this, $acao)){
				$this->actionName = $acao;
				$this->view->setViewName($acao);
				$this->$acao();
			}
			die;
		}
		
		public function getAjaxData($key = null)
		{
			if(empty($key))
				return $this->ajax[self::DATA_KEY];
			
			if(array_key_exists($key,$this->ajax[self::DATA_KEY]))
			return $this->ajax[self::DATA_KEY][$key];
			
			return null;
		}
		
		public function getAjaxAction()
		{
			return $this->ajax[self::PARAMETERS_KEY]["action"];
		}
		
		
		public function getAjaxParameters($key = null)
		{
			if(empty($key))
				return $this->ajax[self::PARAMETERS_KEY];
			return $this->ajax[self::PARAMETERS_KEY][$key];
		}
		
		

		/**
		 * retorna uma instanccia de container
		 */
		public function getContainer()
		{
			return $this->container;
		}
		
		/**
		 * seta uma instancia de container
		 * @param unknown $container
		 * @return System_Controller
		 */
		public function setContainer(System_Container $container)
		{
			$this->container = $container;
			return $this;
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
		
		public static function getUrlName($literalName)
		{
			$container = System_Registry::get("container");
			
			$info = $container->getModuleInfo();
			

			$name = str_replace($info["controllerPrefix"], "", $literalName);
			$name = str_replace($info["controllerSuffix"],"",$name);
			return $name;
		}

	}
?>