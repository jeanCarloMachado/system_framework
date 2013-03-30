<?php
	class System_View implements System_DesignPattern_Singleton_Interface 
	{
		protected static $instance;
		protected $vars = array();
		protected static $name;
		protected $layoutName = "default";
		protected $controllerName = null;
		protected $viewName = null;
		protected $layoutPath = null;
		protected $viewPath = "src/View/public";
		protected $layoutFileName = "_layout.phtml";
		protected $info = null;
		protected static $renderStatus = 1;
		protected $virtualRender = false;
		protected $extraEntitys = array();
		private $layoutStatus = 1;
		
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
		
		private function __construct(){}
		/**
		 * reccebe parametros que são adicionados em vars,
		 * para depois serem pegos nas views
		 * @param unknown $method
		 * @param array $args
		 * @throws Exception
		 */
		public function __call($method, array $args)
		{
			/**
			 * [$methodName description]
			 * @var [type]
			 */
			$attrName = strtolower(substr($method, 3));
				
			$action = substr($method,0,3);
		
			if($action == "get") {
				return $this->getVar($attrName);
			} else if($action == "set") {
				return $this->setVar(reset($args),$attrName);
			}
		
			throw new Exception("método desconhecido (".$attrName.") - verfique System_DB_Table_Row");
		}
		/**
		 * pega uma variável virtual pela key
		 * @param unknown $key
		 * @throws Exception
		 * @return unknown
		 */
		protected function getVar($key)
		{
			if(empty($key))
				throw new Exception("chave da busca não passada");
				
			if(is_array($this->vars) && array_key_exists($key, $this->vars))
				return $this->vars[$key];
				
			return null;
		}
		/**
		 * retorna as entidades html em forma de objeto 
		 * que foram passadas para a vis�o
		 * @return Ambigous <multitype:, multitype:NULL >
		 */
		public function getEntitys()
		{
			$result = array();
				
			$row = $this->getRow();
			if($row) {
				$result =  System_Entity_Manager::getEntityArrayFromRow($row);
			}
			
			$extraEntitys = $this->getExtraEntitys();
			
			
			if(!empty($extraEntitys))
				$result = array_merge($result,$extraEntitys);
			
			return $result;
		}
		
		/**
		 * retorna true se o arquivo de view existe
		 */
		public function viewFileExists() 
		{
			$viewPath = $this->getViewPath()."/".$this->getControllerName()."/".$this->getViewName();
			$result =  sstream_resolve_include_path($viewPath);	
			return $result;
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
			
			if(!$this->getRenderStatus() || substr($this->getViewName(),-10,4) == "Ajax" || substr($this->getViewName(), 0, 5) == "ajax_")
				return null;

			global $endereco_site;
			
			$this->setInfo($info);
			
			if(!empty($info))
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
			//extrai os dados para ficarem no contexto da view
			if(!empty($info))
				extract($info);
			
			
			if($this->viewFileExists() && !$this->getVirtualRender()) {
				
				require_once $viewPath;
			} else {
				
				//seta a flag de renderiza��o virtaul
				$this->setVirtualRender(true);
				$path = $this->getContainer()->getVirtualViewPath();

				require_once $path.$this->getViewName();
			}
		}
		/**
		 * reseta as informaçãos da chamada
		 */
		private function resetInfo()
		{
			$this->setControllerName(null);
			$this->setViewName(null);
		}
		
		public function getExtraEntitys()
		{
			return $this->extraEntitys;
		}
		
		public function setExtraEntitys($arr)
		{
			$this->extraEntitys  = $arr;
			return $this;
		}
		/**
		 * seta uma variavel virtual
		 * @param unknown $key
		 * @param unknown $val
		 */
		protected function setVar($val,$key)
		{
			$this->vars[$key] = $val;
			return $this;
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
			if(!$this->layoutPath)
				throw new Exception("n�o foi setado o caminho para  o layout");
				
				
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
		
		
		public function getContainer()
		{
			$result = System_Registry::get("container");
				
			return $result;
		}
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
		
		public function getVirtualRender()
		{
			return $this->virtualRender;
		}
		
		public function setVirtualRender($status)
		{
			$this->virtualRender = $status;
			return $this;
		}
	}
?>