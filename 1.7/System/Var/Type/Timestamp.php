<?php
	/**
	 * interface to the default types of
	 * the system and it's properly default outputs
	 */
	class System_Var_Type_Timestamp extends System_Var_Type_Abstract
	{
		public function convert($data)
		{
			$tmp = explode(" ", $data);
			$tmp[0] = System_Object_Date::fromMysql($tmp[0]);
			$data = $tmp[0];
			if(!empty($tmp[1]))
				$data .= " ".$tmp[1];
			
			return $data;
		}
		
		public function isValid($data)
		{
			return true;
		}
	}
?>