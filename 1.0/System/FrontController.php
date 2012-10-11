<?php
	class System_FrontController
	{

		/**
		 * nome do controlador
		 * @var [type]
		 */
		private $controllerName;
		/**
		 * nova da visão
		 * @var string
		 */
  		private $viewName;
  		/**
  		 * salva os parametros passados na url
  		 * @var array
  		 */
  		private $_urlParameters;
  		/**
  		 * se o controlador for do 
  		 * ack a flag fica como true;
  		 * @var boolean
  		 */
  		private $_modular;


		private static $instance;

		private function __construct()
		{
		}
		//seta o controlador e a visao atuais
		public function setMVC($controller,$view,$modular)
		{
			$this->controllerName = $controller;
			$this->viewName = $view;
			$this->_modular = $modular;
		}


		//retorna uma instancia da classe
		public static function getInstance()
		{
			if(!self::$instance)
			{
				self::$instance = new System_FrontController();
			}

			return self::$instance;
		}

		//pega o nome do controlador
		public function getControllerName()
        {
            return $this->controllerName;
        }
        
        public function getViewName()
        {
            return $this->viewName;
        }

		public function isModular()
		{
			return $this->_modular;
		}

		                    
		public function getUrlParameters()
		{
		    return $this->_urlParameters;
		}
		
		public function setUrlParameters($urlParameters)
		{
		    $this->_urlParameters = array_values($urlParameters);
		}
	}
		
?>