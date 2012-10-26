<?php
	require_once 'System/Autoloader/Loader/Interface.php';

	class System_Autoloader_Loader_Library implements System_Autoloader_Loader_Interface 
	{	
		/**
		 * testa se através de seu método encontra
		 * o arquivo procurado, caso sim retorna seu nome
		 * senão retorna 0
		 * @param  str     $name   [description]
		 * @param  [type]  $params [description]
		 * @return boolean         [description]
		 */
		public function hasFile(str $name,$params)
		{
			$arrName = explode('_',$name);

			$pathFull = $params['librarysPath'];
			$pathFull.= $params['currLibrary'].'/';

			$path = $params['librarysPath'];

			foreach($arrName as $folder)
			{
				$pathFull.= $folder.'/';
				$path.= $folder.'/';
			}

			$pathFull = substr($pathFull,0, -1);
			$pathFull.= '.php';
			
			$path = substr($path,0, -1);
			$path.= '.php';

			/**
			* se não conseguiu incluir pelo nome completo
			* procura nos diretórios do include path
			*/
			if(file_exists($pathFull))
				return $pathFull;
			else if(file_exists($path)) 
				return $path;

			return 0;
		}
	}
?>