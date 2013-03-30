<?php

	/**
	 * classe relacionada a senhas
	 */
	class System_Object_Encryption 
	{
		
		/**
		 * encripta um arquivo com a segurança do sistema
		 * @param  [type] $str [description]
		 * @return [type]      [description]
		 */
		public static function encrypt($str) 
		{
			return md5($str);
		}
		

		/**
		 * decripta um arquivo com a segurança do sistema
		 * @param  [type] $str [description]
		 * @return [type]      [description]
		 */
		// public static function decrypt($str) 
		// {
		// 	return md5($str);
		// }
	}
?>