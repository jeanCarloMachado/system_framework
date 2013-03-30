<?php
	/**
	 * funções de manipulação de strings
	 * @author jean
	 *
	 */
	class System_Object_String 
	{
	
		/**
		 * pega o nome puro de um controlador
		 * @param unknown $str
		 * @return string
		 */
		public static function getCleanClassName($str)
		{
			if(strtolower(substr($str,0,3)) == "ack") {
				$str = substr($str,3);
			}
			
			
			if(strtolower(substr($str,-11)) == "_controller") {
				$str = substr($str,0,-11);
			}
			return $str;
		}
		
		public function replaceAcentuationForEntity($var, $enc = 'UTF-8')
		{
			 $acentos = array(
			   'a' => '/&Atilde;|&atilde;|&Aacute;|&aacute;|&Acirc;|&acirc;|&Agrave;|&agrave;|&Aring;|&aring;|&Auml;|&auml;|&ordf;/',
			   'c' => '/&ccedil;|&Ccedil;/',
			   'e' => '/&Eacute;|&eacute;|&Ecirc;|&ecirc;|&Egrave;|&egrave;|&Euml;|&euml;/',
			   'i' => '/&Iacute;|&iacute;|&Icirc;|&icirc;|&iexcl;|&Igrave;|&igrave;|&Iuml;|&iuml;/',
			   'n' => '/&Ntilde;|&ntilde;/',
			   'o' => '/&Oacute;|&oacute;|&Ocirc;|&ocirc;|&Ograve;|&ograve;|&Oslash;|&oslash;|&ordm;|&Otilde;|&otilde;|&Ouml;|&ouml;/',
			   'u' => '/&Uacute;|&uacute;|&Ucirc;|&ucirc;|&Ugrave;|&ugrave;|&Uuml;|&uuml;/',
			   'y' => '/&Yacute;|&yacute;|&yen;|&Yuml;|&yuml;/');
			   
			 $var = preg_replace($acentos, array_keys($acentos), htmlentities($var, ENT_NOQUOTES, $enc));
			 //$var = strtolower($var);
			 return $var;
		}

		/**
		 * retorna uma string com suas palavras com o primeiro elemento em caixa alta
		 * @param  string $str [description]
		 * @return [type]      [description]
		 */
		public static function upWords(string $str) 
		{

			$str = strtolower($str);
			$str = preg_replace('#\s(como?|d[aeo]s?|desde|para|por|que|sem|sob|sobre|trás)\s#ie', '" ".strtolower("\1")." "', ucwords($str));

			return $str;	
		}
		
		/**
		 * (MELHORAR ESSA FUNÇÃO)
		 * procura similaridades entre calledName e name
		 * removendo os sufixos de name
		 * @param unknown $calledName
		 * @param unknown $arrayNames
		 */
		public static function matchWithoutSuffixes($calledName,$calledName)
		{
			$calledName = strtolower($calledName);
			$name = strtolower($name);
			
			
		   if($calledName == $name)
				return $name;
		   
		   $oldName = $name;
		   $oldCalledName = $calledName;
			
		   //testa se o name é explodível
		   {
				$name = explode("_",$name);
				
				if(count($name) <= 1 )
					return null;
					
				if($calledName == $name[count($name) -1])
					return $oldName;
		   }
			return null;
		}
		
		/**
		 * testa se determinada string tem sufixo de
		 * algum idioma no sistema
		 * @param unknown $string
		 */
		public static function hasLangSuffix($string)
		{
			$container = System_Container::getInstance();
			$langSuffixes = $container->getLanguageSuffixes();
			
			
			$string = explode("_", $string);
			if(count($string) <= 1)
				return false;
			
			
			$string = end($string);
			
			if(in_array($string, $langSuffixes))
				return $string;
			
						
			return false;
		}
		
	}
?>