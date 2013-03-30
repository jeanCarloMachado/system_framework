<?php
	/**
	 * interface to the default types of
	 * the system and it's properly default outputs
	 */
	class System_Var_Type_Float extends System_Var_Type_Abstract
	{
		public function convert($data)
		{
			if(!$data)
				$result = System_Object_Number::toBr(0);
			
			$result = System_Object_Number::toBr($data);		
			
			return $result;
		}
		
		public function isValid($data)
		{
			return true;
		}
	}
?>