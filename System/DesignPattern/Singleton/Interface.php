<?php
	
	interface System_Singleton_Interface {

		/**
		 * construct deve ser privado
		 */
		function __construct();

		/**
		 * get instance deve ser público
		 * @return [type] [description]
		 */
		static function getInstance();

	}
?>