<?php
	class System_Object_String 
	{

		/**
		 * remove acentuação de uma string
		 * @param  [type] $string [description]
		 * @return [type]         [description]
		 */
		// public function replaceAcentuationForEntity($var, $enc = 'UTF-8')
		// {
		// 	 $acentos = array(
		// 	   'a' => '/À|Á|Â|Ã|Ä|Å/',
		// 	   'a' => '/à|á|â|ã|ä|å/',
		// 	   'c' => '/Ç/',
		// 	   'c' => '/ç/',
		// 	   'e' => '/È|É|Ê|Ë/',
		// 	   'e' => '/è|é|ê|ë/',
		// 	   'i' => '/Ì|Í|Î|Ï/',
		// 	   'i' => '/ì|í|î|ï/',
		// 	   'n' => '/Ñ/',
		// 	   'n' => '/ñ/',
		// 	   'o' => '/Ò|Ó|Ô|Õ|Ö/',
		// 	   'o' => '/ò|ó|ô|õ|ö/',
		// 	   'u' => '/Ù|Ú|Û|Ü/',
		// 	   'u' => '/ù|ú|û|ü/',
		// 	   'y' => '/Ý/',
		// 	   'y' => '/ý|ÿ/',
		// 	   'a.' => '/ª/',
		// 	   'o.' => '/º/');
		// 	 $var = preg_replace($acentos, array_keys($acentos), htmlentities($var, ENT_NOQUOTES, $enc));
		// 	 //$var = strtolower($var);
		// 	 return $var;
		// }


		function replaceAcentuationForEntity($var, $enc = 'UTF-8')
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

		function upwords($str) {

			$str = strtolower($str);
			return preg_replace('#\s(como?|d[aeo]s?|desde|para|por|que|sem|sob|sobre|trás)\s#ie', '" ".strtolower("\1")." "', ucwords($str));	
		}


		// function unaccentHtmlEntity2($string)
		// {
		// 	$string = utf8_encode($string);

		// 	if (strpos($string = htmlentities($string, ENT_QUOTES, 'UTF-8'), '&') !== false)
		// 	{
		// 		$string = html_entity_decode(preg_replace('~&([a-z]{1,2})(?:accute|cedil|circ|grave|lig|orn|ring|slash|tilde|uml);~i', '$1', $string), ENT_QUOTES, 'UTF-8');
		// 	}

		// 	return $string;
		// }




	}
?>