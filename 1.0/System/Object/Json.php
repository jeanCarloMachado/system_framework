<?php
	class System_Object_Json 
	{
		public static function read($str) 
		{
			if ($converteURL) {
			 $json=urldecode($json);
			}
			if (get_magic_quotes_gpc()) {
				$json=stripslashes($json);
				return json_decode($json, true);
			} else {
				return json_decode($json, true);
			}
		}


		public static function create(array $arr) 
		{
			return json_encode($data);
		}
	}
?>