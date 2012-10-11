<?php
	/**
	 * adiciona um acesso a um modelo a cada abertura de controlador
	 */
	class System_Plugin_AccessNumber extends System_Plugin_Abstract implements System_Config_Configurable_Interface
	{
		/**
		 * retorna a configuração da classe 
		 * @param  [type] $config [description]
		 * @return [type]         [description]
		 */
		public function getConfig() 
		{
		
			$config = System_Config::get();
			
			return $config->system->plugin->accessNumber;
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


		public function _autoEnable() 
		{
			$config = $this->getConfig();

			if($config->status) {
				return true;
			}

			return false;
		}

		protected function run(array $params) 
		{
				$config = $this->getConfig();
				/**
				 * insere na classe passada pelo arquivo de configuração o ip e a data
				 */
				$className = $config->class;
				/** pega o nome do método */
				$method = $config->method;

				$where = array();
				$where[$config->column->ip->name] = $_SERVER['REMOTE_ADDR'];
				$where[$config->column->data->name] = date('Y-m-d h:i:s');

				$obj = new $className;
				$result = $obj->$method($where);

		}
	}
?>