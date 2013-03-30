<?php
	/**
	 * objeto que faz um request de post
	 */
	class System_Object_Post
	{	
		public static function jsonRequest($url,$params=null,$prefix)
		{

			if(!$prefix)
				return false;

			/**
			 * codifica os parametros
			 * @var [type]
			 */
			$params = json_encode($params);
			$data = array($prefix=> $params);
			$data = http_build_query($data);
		
			/**
			 * monta o cabeçalho
			 * @var array
			 */
			$opts = array(
			    'http' => array(
			        'method' => "POST",
			        'header' => "Connection: close\r\n".
			                    "Content-type: application/x-www-form-urlencoded\r\n".
			                    "Content-Length: ".strlen($data)."\r\n",
			        'content' => $data
			  )
			);

			$context  = stream_context_create($opts);
			$result = file_get_contents($url, false, $context);

			return $result;
		}
	}

?>