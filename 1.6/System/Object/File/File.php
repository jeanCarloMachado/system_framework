<?php
	require_once "System/Object/File/Interface.php";

	class System_Object_File implements System_Object_File_Interface
	{
		
		private $_type;
		private $_path;

		public function __construct($path)
		{
			$this->setPath($path);

			/**
			 * pega o tipo do arquivo à partir do path
			 */
			
			$arr = explode('.',$path);
			$arr = array_reverse($arr);
			$this->setType($arr[0]);

		}

		/**
		 * seta o tipo de um arquivo
		 */
		public function setType($type)
		{
			$this->_type = $type;
		}

		/**
		 * pega o tipo de um arquivo
		 * @param string $type [description]
		 */
		public function getType() 
		{
			return $this->_type;
		}

		/**
		 * seta o caminho do arquivo (deve contér o nome também)
		 * @param [type] $path [description]
		 */
		public function setPath($path)
		{
			$this->_path = $path;
		}

		/**
		 * pega o caminho do arquivo (contém o nometambém)
		 * @return [type] [description]
		 */
		public function getPath()
		{
			return $this->_path;
		}

		/**
		 * seta permissao
		 * @param  [type] $permission [description]
		 * @param  [type] $file       [description]
		 * @param  [type] $params     [description]
		 * @return [type]             [description]
		 */
		public static function chmod($permission,$file,$params=null) 
		{
			$files = glob($file); 
			if(empty($files))
				return false;		

			foreach($files as $file) { 
				chmod ($file, $permission);
			}

			return true;
		}

		/**
		 * remove arquivos
		 * @param  [type] $params [description]
		 * @param  [type] $file   [description]
		 * @return [type]         [description]
		 */
		public static function rm($file,$params=null) 
		{
			$files = glob($file); 

			if(empty($files))
				return false;		

			foreach($files as $file) { 
				if(is_file($file)) {
					@chmod($file, 0777);
					@unlink($file);
				}
			}

			return true;
		}
	}
?>