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
		public function hasFile($name,$params)
		{
			/**
			 * testa se encontra o arquivo na bilioteca definida
			 */
			$path = $params['librarysPath'];
			$path.= $params['currLibrary'].'/';			
			$path.= self::getPath($name);
				
			if(file_exists($path)) {
				return $path;
			} else {
				/**
				 * testa se encontra o arquivo sem a bliblioteca (no include path default)
				 */
				$path = $params['librarysPath'];
				$path.= self::getPath($name);

				if(file_exists($path)) {
					return $path;
				} else {
					/**
					 * testa se eoncotra o arquivo na bliblioteca definida com o caminho fake
					 */
					$path = $params['librarysPath'];
					$path.= $params['currLibrary'].'/';	
					$path.= self::getFakePath($name);
					if(file_exists($path))
						return $path;
					else {

						/**
						 * testa se encontra o arquivo sem a bliblioteca (no include path default) com o caminho fake
						 */
						$path = $params['librarysPath'];
						$path.= self::getFakePath($name);
						if(file_exists($path))
							return $path;
						else {
							/**
							 * inclui o default com qualquer camihnho
							 * definido no include path
							 * @var [type]
							 */
							$path= self::getPath($name);
							return sstream_resolve_include_path($path);
						}
					}
				}
			}
			return 0;
		}

		/**
		 * retona o caminho no padrão da biblioteca
		 * @param  [type] $fileName [description]
		 * @return [type]           [description]
		 */
		public static function getPath($fileName)  
		{
			$arrName = explode('_',$fileName);
			
			$result = "";
			foreach($arrName as $folder)
			{
				$result.= $folder.'/';
			}

			$result = substr($result,0, -1);
			$result.= '.php';

			return $result;
		}

		/**
		 * retona o caminho falso da biblioteca
		 * ex: System_Controller se encontra em System_Controller_Controller
		 * dá include do system_controller_controller
		 * @param  [type] $fileName [description]
		 * @return [type]           [description]
		 */
		public static function getFakePath($fileName)  
		{
			$arrName = explode('_',$fileName);
			
			$result = "";
			foreach($arrName as $folder)
			{
				$result.= $folder.'/';
			}
			$result.= end($arrName);

			$result.= '.php';

			return $result;
		}
	}
?>