<?php 
	require_once 'System/Log/Interface.php';

	class System_Log implements System_Log_Interface {

		/**
		 * nome do arquivo de cache
		 * @var string 
		 */
		private $_fileName;

		public function __construct($name) {
			$this->_fileName = $name;
		}

		public function append($content) {
			$file_handle = fopen($this->_fileName,'a');

			if(!$file_handle) 
				trigger_error("nao conseguiu abrir o arquivo $this->_fileName");

			fwrite($file_handle,$content);
			fclose($file_handle);
		} 
	}	

?>