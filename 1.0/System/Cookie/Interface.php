<?php
	interface System_Cookie_Interface 
	{
		/**
		 * cria um novo cookie e retorna o seu objeto
		 * @param  string  $name    [description]
		 * @param  string  $value   [description]
		 * @param  [type]  $domain  [description]
		 * @param  integer $expires [description]
		 * @param  [type]  $path    [description]
		 * @param  [type]  $secure  [description]
		 * @return [type]           [description]
		 */
		public function create ($name, $value);

		/**
		 * remove o cookie
		 * @param  [type] $cookieObj [description]
		 * @return [type]            [description]
		 */
		public function remove(string $name);

		/**
		 * le o cookie
		 * @param  [type] $cookieObj [description]
		 * @return [type]            [description]
		 */
		public function read(string $name);

	
		public function getDefaultPath();
		
		
		public function setDefaultPath($defaultPath);
		
		public function getDuration();


		public function setDuration($duration);
	}
?>