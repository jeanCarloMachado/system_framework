<?php
	interface System_Object_File_Interface
	{
		/**
		 * seta o tipo de um arquivo
		 */
		public function setType($type);

		/**
		 * pega o tipo de um arquivo
		 * @param string $type [description]
		 */
		public function getType();

		/**
		 * seta o caminho do arquivo (deve contér o nome também)
		 * @param [type] $path [description]
		 */
		public function setPath($path);

		/**
		 * pega o caminho do arquivo (contém o nometambém)
		 * @return [type] [description]
		 */
		public function getPath();
	}
?>