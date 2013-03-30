<?php
	/**
	 * interface to the default types of
	 * the system and it's properly default outputs
	 */
	interface System_Var_Interface 
	{
		public function getValue();
		public function getVal();
		public function setValue($value);

		public function getBruteValue();
		public function setBruteValue($value);

		public function getType();
		public function setType($type=null);
	}
?>