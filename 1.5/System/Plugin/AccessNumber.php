<?php
	/**
	 * adiciona um acesso a um modelo a cada abertura de controlador
	 */
	class System_Plugin_AccessNumber extends System_Plugin_Abstract implements System_Config_Configurable_Interface
	{
		/**
		 * delay para reincrementar o contador
		 */
		const INCREMENT_HOURS_DELAY = 12;
		const DEFAULT_DATE_FORMAT = 'Y-m-d H:i:s';
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

				$where = array();
				$where[$config->column->ip->name] = $_SERVER['REMOTE_ADDR'];

				$obj = new $className;

				$myLastAccess = ($obj->get($where,array('order'=>'data DESC','limit'=>array('count'=>1))));

				if(!empty($myLastAccess[0]))
					$myLastTime = (strtotime($myLastAccess[0]['data']));
				else 
					$myLastTime = 0;
				/**
				 * se a hora atual for maior que a última+12 então reincrementa
				 * os acessos para aquele ip
				 */
				if(((int)$myLastTime+(self::INCREMENT_HOURS_DELAY * 3600) < (int)strtotime(date(self::DEFAULT_DATE_FORMAT))))
				{	
					$where[$config->column->data->name] = date(self::DEFAULT_DATE_FORMAT);
					$obj->create($where);
				}
		}
	}
?>