<?php
	class Reuse_Pacman_View_Helper_Retrieve_ControllerInfo
	{
		protected $controllerBasePath;
		protected $blackListed = array();
	
	
		public function __construct()
		{
				
		}
	
		public function setControllerBasePath($path)
		{
			$this->controllerBasePath = $path;
			return $this;
		}
		public function getControllerBasePath()
		{
			if(empty($this->controllerBasePath)) {
				
				$container = System_Registry::get("container");
				$path = $container->getControllerPath();
				$this->controllerBasePath = $path;
			}
			
			return $this->controllerBasePath;
		}
	
		/**
		 * retorna os nomes dos controladores
		 */
		public function getControllersNames()
		{
			$files = array();
			$files = glob($this->getControllerBasePath()."/*.php");
			
			foreach($files as $fileId => $file) {
				@$files[$fileId] = end(explode("/",$file));
				@$files[$fileId] = reset(explode(".",$files[$fileId]));
			}
				
			return $files;
		}
	
		public function run()
		{
			$controllersNames = $this->getControllersNames();
			$container = System_Registry::get("container");
			
			
			$result = null;
			foreach($controllersNames as $controllerName) {
				
				$result[] = array("url"=>VIRTUAL_PATH."/".$container->getModuleName()."/".$controllerName::getUrlName($controllerName),"title"=>$controllerName::TITLE);
			}
			
			return $result;
		}
	}