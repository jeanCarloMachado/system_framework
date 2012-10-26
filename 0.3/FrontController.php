<?php

	/*
		usando:
		require_once 'includes/library/FrontController.php';
    $front = FrontController::getInstance();
    $currentView = $front->getViewName();
	*/

	class FrontController
	{
		private $controllerName;
        private $viewName;
		private static $instance;

		private function __construct()
		{
		}
		//seta o controlador e a visao atuais
		public function setMVC($controller,$view)
		{
			$this->controllerName = $controller;
			if(strlen($controller)<2)
				$this->controllerName = 'home';
			
			$this->viewName = isset($view) ? $view : 'index';
		}

		//retorna uma instancia da classe
		public static function getInstance()
		{
			if(!self::$instance)
			{
				self::$instance = new FrontController();
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
	}
		
?>