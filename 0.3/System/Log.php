<?php 
	require_once 'Log/Interface.php';

	class System_Log implements System_Log_Interface {

		/**
		 * nome do arquivo de cache
		 * @var string 
		 */
		private $_fileName;

		private $_logFlag;

		public function __construct($name) {

			$this->_logFlag = SYSTEM_LOG;

			if($this->_logFlag) {

				$this->_fileName = $name;
			}

			return false;
		}

		public function append($content) {

			if($this->_logFlag) {

				$file_handle = fopen($this->_fileName,'a');

				if(!$file_handle) 
					trigger_error("nao conseguiu abrir o arquivo $this->_fileName");

				fwrite($file_handle,$content);
				fclose($file_handle);

			}

			return false;
		} 
	}	

?>