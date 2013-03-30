<?php
	/**
	 * interface to the default types of
	 * the system and it's properly default outputs
	 */
	class System_Var_Type_Int extends System_Var_Type_Abstract
	{
		public function convert($data)
		{
			$colName = $this->getVar()->getColumnName();
			
			if($colName == "id")
				if(!$data)
					$data  = "";
				else
				System_Object_Number::putDigitsInfront($data);
			
			
			if(!$data)
				return "0";
			
			
			return $data;
		}
		
		public function isValid($data)
		{
			return true;
		}
	}
?>