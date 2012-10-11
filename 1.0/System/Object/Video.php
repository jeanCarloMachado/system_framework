<?php
	require_once 'System/Object/Video/Interface.php';

	class System_Object_Video implements System_Object_Video_Interface
	{
		private $_val;

		/**
		 * pega a string identificadora de um vídeo
		 * à partir de uma url
		 * @param  [type] $url [description]
		 * @return [type]      [description]
		 */
		public static function getIdetificator($url) 
		{
			if(!is_string($url)) 
			return null;


			$matches = array();
			$pattern='#(\.be/|/embed/|/v/|/watch\?v=)([A-Za-z0-9_-]{5,11})#';
			preg_match($pattern, $url, $matches);
			
			return $matches[2];
		}
	}
?>