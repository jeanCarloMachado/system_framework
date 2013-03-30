<?php
	/**
	 * um plugin se diferencia por ser um serviço prestado
	 * interface padrão para todos os plugins do sistema
	 */
	abstract class System_Plugin_Abstract
	{	

		public function __construct() 
		{
			/**
			 * testa se o plugin estará ativado ou não
			 * @var [type]
			 */
			$result = $this->_autoEnable();
			if($result) {
				$this->enable();
			} else {
				$this->disable();
			}
		}

		protected function _autoEnable() 
		{
			return 1;
		}

		private $_status = 0;
		
		public final function enable()
		{
			$this->_status = 1;
		}


		public final function disable()
		{
			$this->_status = 0;
		}


		/**
		 * testa se o plugin está habilitado
		 * @return boolean [description]
		 */
		final function isEnabled() 
		{
			return $this->_status;
		}

		/**
		 * cham a funcao chamada por funcName
		 * @param  str    $funcName [description]
		 * @return [type]           [description]
		 */
		public final function load(array $params)
		{
		
			if(!$this->isEnabled()) {
				return false;
			}	

			$this->run($params);
		}


		/**
		 * função que deve ser implementada pelo usuário
		 * faz o plugin funcionar propriamente dito
		 * @param  array  $params [description]
		 * @return [type]         [description]
		 */
		public abstract function run($params);
	}
?>