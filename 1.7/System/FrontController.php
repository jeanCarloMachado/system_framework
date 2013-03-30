<?php
	class System_FrontController
	{
		/**
		 * nome do controlador
		 * @var [type]
		 */
		private static $controllerName  = null;
		private static $moduleName = null;
		private static $controllerNameDefault = "index";
		private static $viewNameDefault = "index";
		/**
		 * nova da visão
		 * @var string
		 */
  		private static $viewName = "index" ;
  		/**
  		 * salva os parametros passados na url
  		 * @var array
  		 */
  		private static $_urlParameters = array();
  		/**
  		 * se o controlador for do 
  		 * ack a flag fica como true;
  		 * @var boolean
  		 */
  		private static $_modular;

  		/**
  		 * instancia singleton
  		 * @var [type]
  		 */
		private static $instance;

		private function __construct()
		{
		}
		
		/**
		 * seta controlador e visão atual
		 * @param [type] $controller [description]
		 * @param [type] $view       [description]
		 * @param [type] $modular    [description]
		 */
		public function setMVC($controller,$view,$modular)
		{
			self::$controllerName = $controller;
			self::$viewName = $view;
			self::$_modular = $modular;
		}


		/**
		 * retorna uma instancia da classe
		 * @return [type] [description]
		 */
		public static function getInstance()
		{
			if(!self::$instance)
			{
				self::$instance = new System_FrontController();
			}

			return self::$instance;
		}

		/**
		 * seta os parametros da url
		 * @param [type] $urlParameters [description]
		 */
		public function setUrlParameters($urlParameters)
		{
		    self::$_urlParameters = array_values($urlParameters);
		}

		public function isModular()
		{
			return self::$_modular;
		}

		/**
		 * retorna  true se o controlador 
		 * autual pertence ao front do sistema
		 * @return boolean
		 */
		public static function isFrontSite()
		{
			return !self::$_modular;
		}
		
		/**
		 * retorna o nome do controlador
		 * @return [type] [description]
		 */
		public static function getControllerName()
        {
            return (empty(self::$controllerName)) ? self::$controllerNameDefault : self::$controllerName;
        }
        
        
        
        /**
         * retorna o nome da visao
         * @return [type] [description]
         */
        public static function getViewName()
        {
            return (empty(self::$viewName)) ? self::$viewNameDefault : self::$viewName;
        }

		/**
		 * retorna so parametros passados na url
		 * @return [type] [description]
		 */
		public static function getUrlParameters()
		{
		    return self::$_urlParameters;
		}
		
		public static function getModuleName()
		{
			return self::$moduleName;
		}
		
		public static function setModuleName($module)
		{
			self::$moduleName = $module;
		}
		
	}
		
?>