<?php
	interface System_Autoloader_Loader_Interface 
	{	
		/**
		 * testa se através de seu método encontra
		 * o arquivo procurado, caso sim retorna seu nome
		 * senão retorna 0
		 * @param  str     $name   [description]
		 * @param  [type]  $params [description]
		 * @return boolean         [description]
		 */
		public function hasFile(str $name,$params);
	}
?>