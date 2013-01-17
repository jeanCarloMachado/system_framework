<?php
	
	/**
	 * gerencia objetos de cookie do zend framework
	 */
	class System_Cookie implements System_Cookie_Interface, System_DesignPattern_Factory_Interface
	{

	
		/**
		 * path default dos cookies
		 * @var [type]
		 */
		private $_defaultPath = null;

		private $_duration = 0;

		private $_domain = "";

		private $_params = null;
		
		
		public static function Factory($params=null) 
		{
			$sysCookie = new System_Cookie;

			$sysCookie->_params = $params;

			$sysCookie->setDuration(time()+31556926);
			$sysCookie->setDefaultPath(null);

			$sysCookie->create($sysCookie->_params['name'],$sysCookie->_params['value']);

			return $sysCookie;
		}


		public function create($name, $value) 
		{
			setcookie($name, $value, $this->_duration, $this->_defaultPath, $this->_domain);
			return true;
		}

		public function read(string $name) 
		{
			return $_COOKIE[$name];
		}

		public function remove(string $name) 
		{
			unset ($_COOKIE[$name]);
		}

		public function setParams($params) 
		{
			$this->_params  = $params;
		}

		/**
		 * pega um parametro pela chave de busca
		 * @param  [type] $key [description]
		 * @return [type]      [description]
		 */
		function getParam($key) 
		{
			if(array_key_exists($key, $this->_params)) {
				return $this->_params[$key];
			}

			return null;
		}

		public function getDefaultPath()
		{
		    return $this->_defaultPath;
		}
		
		public function setDefaultPath($defaultPath)
		{
		    $this->_defaultPath = $defaultPath;
		}

		public function getDuration()
		{
		    return $this->_duration;
		}
		
		public function setDuration($duration)
		{
		    $this->_duration = $duration;
		}
		
		

	}
?>