<?php
	/**
	 * interface to the default types of
	 * the system and it's properly default outputs
	 */
	interface System_Var_Type_Interface 
	{
		public function convert($data);
		public function isValid($data);
		public function getAlias();
		public function setAlias($alias);
	}
?>