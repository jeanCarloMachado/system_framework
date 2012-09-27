<?php
	class System_Object_String 
	{

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
	}
?>