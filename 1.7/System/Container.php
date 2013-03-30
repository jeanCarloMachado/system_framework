<?php
	/**
	 *  responsável por fazer a realização de pendendencia das classes
	 */
	class System_Container implements System_Container_Interface
	{	
		protected $modules = null;
		protected $config = null;
		protected $currRow;

		
		public function getLanguageSuffixes()
		{
			return array("pt","en","es");
		}
		
		public function setCurrentRow(System_Db_Table_AbstractRow &$row)
		{
			$this->currRow = $row;
			return $this;
		}
		
		/**
		 * retorna a linha atual
		 * @throws Exception
		 * @return System_Db_Table_AbstractRow
		 */
		public function getCurrentRow()
		{
			return $this->currRow;
		}
		
		/**
		 * instancia um plugin de controle de acesso;
		 */
		public function getAcessNumberPluginInstance()
		{
			$obj = new System_Plugin_AccessNumber;
			
			$config = $this->getServicesConfig();
			
			$obj->setModelName($config["accessNumber"]["modelName"]);
			
			return $obj;
		}
		
		/**
		 * retorna o array de configurações de servicos
		 * @return Ambigous <[type], multitype:>
		 */
		public function getServicesConfig() 
		{
			$result = @System_Config_Array::get("services");
			if(empty($result)) {
				System_Config_Array::register(require_once "../config/services.php","services");
			}
			$result = System_Config_Array::get("services");
			return $result;
		}
		
		/**
		 * retornas os m�dulos dispon�veis no sistema
		 * caso for passado key ent�o retorna
		 * somente as informa��es daquele m�dulo
		 */
		public function getModules($key=null)
		{
			if(!$this->modules) {
				$config = $this->getGlobalConfig();
				$this->modules = $config["modules"];
			}
		
			if($key)
				return $this->modules[$key];
				
			return $this->modules;
		}
		
		/**
		 * seta os m�dulos dispon�veis no sistema
		 * @param unknown $modules
		 * @return Container
		 */
		public function setModules($modules)
		{
			$this->modules = $modules;
			return $this;
		}
		
		/**
		 * retorna o config global do sistema
		 */
		public function getGlobalConfig()
		{
			if(!$this->config) {
				
				$this->config = System_Config_Array::get();
				
				//da merge do array global
				if(!empty($this->config)) {
					$this->config = array_merge($this->config, System_Config_Array::get("local"));
				}
				
			}
				
			return $this->config;
		}
		
		public function getDatabaseConfig()
		{
			$result =null;
			$result = $this->getGlobalConfig();
			return $result["db"];
		}
		
		/**
		 * retorna o path do layout
		 * de acordo com o m�dulo atual
		 * @return string
		 */
		public function getLayoutPath()
		{
			$moduleName = System_FrontController::getModuleName();
			$config = $this->getGlobalConfig();
			$path = $config["modules"][$moduleName]["layoutPath"];
			return $path;
		}
		
		
		/**
		 * retorna o path do layout
		 * de acordo com o m�dulo atual
		 * @return string
		 */
		public function getModuleInfo()
		{
			$moduleName = System_FrontController::getModuleName();
			$config = $this->getGlobalConfig();
			$path = $config["modules"][$moduleName];
			return $path;
		}
		
		
		public function getViewPath()
		{
			$moduleName = System_FrontController::getModuleName();
			$config = $this->getGlobalConfig();
			$path = $config["modules"][$moduleName]["viewPath"];
			return $path;
		}
		
		/**
		 * retorna a variavel de ambiente do
		 	* sistema
		 */
		public function getEnvironment()
		{
			$result = System_Config_Array::get();
			return $result["environment"];
		}
		
		public function getBootstrapName()
		{
			$result = $this->getGlobalConfig();
			return $result["boostrapName"];
		}
		
		public function getJsonInfo()
		{
			$result = $this->getGlobalConfig();
			return $result["json"];
		}
		
		
		public function getEntityInstance($name)
		{
			$prefix = "System_Entity_";
			$className = $prefix.$name;
			
			return new $className;
		}
		
		public function getVirtualPath()
		{
			$config = $this->getGlobalConfig();
			return $config["paths"]["endereco_virtual"];
		}
		
		public function getRealPath()
		{
			$config = $this->getGlobalConfig();
			return $config["paths"]["endereco_fisico"];
		}
		
		public function getModuleName()
		{
			$moduleName = System_FrontController::getModuleName();
			return $moduleName;
		}
	}
?>