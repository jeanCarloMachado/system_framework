<?php

	/**
	 * classe relacionada a senhas
	 */
	class System_Object_Encryption 
	{
		/**
		 * String de salt para con catenacão da string
		 * @var String
		 */
		static $salt = "ProJecTDestruCtiOn$%@%";
		/**
		 * encripta um arquivo com a segurança do sistema
		 * @param  [string] $str string a ser encriptada
		 * @param  [string] $hash nome do metodo de encriptacao
		 * @return [hash]      [description]
		 */
		public static function encrypt($str, $hash = "sha512")
		{
			if(function_exists("hash_algos") and in_array($hash,hash_algos())){
				$result = hash($hash,self::$salt.$str);
				#repeticão da criptografia 1k
				for($i=0; $i<1000; $i++)
					$result = hash($hash, $result);
				return $result;
			}else{
				$result = hash('md5',self::$salt.$str);
				for($i=0; $i<1000; $i++)
					$result = hash('md5', $result);
				return $result;
			}
			
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