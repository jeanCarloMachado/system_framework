<?php
	/**
	 * interface to the default types of
	 * the system and it's properly default outputs
	 */
	class System_Var_Type_Default extends System_Var_Type_Abstract
	{
		public function convert($data)
		{
			if(!$data)
				$data = "";
			
			return $data;
		}
		
		public function isValid($data)
		{
			return true;
		}
	}
?>