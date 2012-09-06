<?php
	
	/**
	 * clase abstrata de bootstrap
	 */
	abstract class System_Application_Bootstrap_Bootstrap implements System_Application_Bootstrap_Interface
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