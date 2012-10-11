<?php
	
	/**
	 * clase abstrata de bootstrap
	 */
	class System_Application_Bootstrap implements System_Application_Bootstrap_Interface
	{

		/** construtor de bootstrap default */
		public final function __construct()
		{
			$this->_initBootstrap();
		}

		public function _initBootstrap() 
		{

		}
	}