<?php
	/**
	 * interface to the default types of
	 * the system and it's properly default outputs
	 */
	class System_Var_Type_Int extends System_Var_Type_Abstract
	{
		public function convert($data)
		{
			return $data;
		}
		
		public function isValid($data)
		{
			return true;
		}
	}
?>