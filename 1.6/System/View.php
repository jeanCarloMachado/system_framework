<?php
	class System_View implements System_DesignPattern_Singleton_Interface {
		
		
		private function __construct()
		{
		}
		
		private static $instance;
		/**
		 * get instance deve ser público
		 * @return [type] [description]
		 */
		static function getInstance()
		{
			if(!self::$instance)
				self::$instance = new System_View;
			
			return self::$instance;
		}
		
		private static $name;
		/**
		 * nome do layout a ser carregado
		 * @var unknown
		 */
		private $layoutName = "default";
		private $controllerName = null;
		private $viewName = null;
		/**
		 * caminho default para o layout
		 * @var unknown
		 */
		private $layoutPath = "src/View/public/layout";
		/**
		 * caminho base para a visao
		 * @var unknown
		 */
		private $viewPath = "src/View/public";
		private $layoutFileName = "_layout.phtml";
		private $info = null;
		private $layoutStatus = 1;
		
		private static $renderStatus = 1;
		
		
		public function getLayoutStatus()
		{
			return $this->layoutStatus;
		}
		
		public function setLayoutStatus($status)
		{
			$this->status = $status;
			return $this;
		}
		
		public function disableLayout()
		{
			$this->layoutStatus = 0;
		}
 
		public function getRenderStatus()
		{
			return self::$renderStatus;
		}
		
		public function setRenderStatus($name)
		{
			self::$renderStatus = $name;
			return $this;
		}
		
		public function getLayoutPath()
		{
			return $this->layoutPath;
		}
		
		public function setLayoutPath($name)
		{
			$this->layoutPath = $name;
			return $this;
		}
		
		public function getLayoutFileName()
		{
			return $this->layoutFileName;
		}
		
		public function setLayoutFileName($name)
		{
			$this->layoutFileName = $name;
			return $this;
		}
		
		public function getLayoutName()
		{
			return $this->layoutName;
		}
		
		public function setLayoutName($name)
		{
			$this->layoutName = $name;
			return $this;
		}
		
		public function getInfo()
		{
			return $this->info;
		}
		
		public function setInfo($name)
		{
			$this->info = $name;
			return $this;
		}
		
		public function getControllerName()
		{
			if(empty($this->controllerName)) {
				$this->controllerName = System_FrontController::getControllerName();
			}
			
			
			
			return $this->controllerName;
		}
		
		public function setControllerName($name)
		{
			$this->controllerName = $name;
			return $this;
		}
		
		public function getViewName()
		{
			
			if(empty($this->viewName)) {
				$this->viewName = System_FrontController::getViewName().".phtml";
			}
			
			return $this->viewName;
		}
		
		public function setViewName($name)
		{
			$this->viewName = $name.".phtml";
			return $this;
		}
		
		public function getViewPath()
		{
			return $this->viewPath;
		}
		
		public function setViewPath($name)
		{
			$this->viewPath = $name;
			return $this;
		}
		
		public static function getName()
		{
			return self::$name;
		}
		
		public static function setName($name)
		{
			self::$name = $name;
		}
		
		/**
		 * rendereiza o layout
		 * @param unknown $info
		 * @return NULL
		 */
		public function render($info)
		{
			if(!$this->getLayoutStatus())  {
				
				$this->showContent();
				return;
			}
			
			if(!$this->getRenderStatus() || substr($this->getViewName(), 0, 5) == "ajax_")
				return null;

			global $endereco_site;
			
			$this->setInfo($info);
			extract($info);
			
			$path = $this->getLayoutPath()."/";
			$path.= ($this->getLayoutName() == "default") ? "" : $this->getLayoutName()."/";
			$path.= $this->getLayoutFileName();
			
			require_once $path;
		
		}	
		
		/**
		 * mostra o conteúdo da view 
		 */
		public function showContent()
		{
			$viewPath = $this->getViewPath()."/".$this->getControllerName()."/".$this->getViewName();

			$info = $this->getInfo();
			extract($info);
			
			require_once $viewPath;
			
			//$this->resetInfo();
		}
		
		/**
		 * reseta as informaçãos da chamada
		 */
		private function resetInfo()
		{
			$this->setControllerName(null);
			$this->setViewName(null);
		}
			
		
		
		/**
		 * carrega a view manualmente (arrumar essa coisa)
		 * @param unknown $viewNameName
		 * @param string $vars
		 * @throws Exception
		 */
		public static function load($viewName,$vars = null)
		{	
			global $endereco_site;
			global $endereco_fisico;
			global $lang;
			
			
			extract($vars);
			$url = "src/View/".$viewName.".phtml";
			include_once ($url);
		}	
		
	}
?>