<?php

	class System_Object_CSV implements System_DesignPattern_Factory_Interface {

		/**
		 * nome do arquivo csv
		 * @var [type]
		 */
		private $_fileName;
		/**
		 * separador do arquivo
		 * @var string
		 */
		private $_separator =  ",";
		/**
		 * total de linhas para pegar do arquivo
		 * @var integer
		 */
		private $_rowsToGet = 1;
		/**
		 * linha atual para ler no arquivo
		 * @var integer
		 */
		private $_currRow = 0;

		/**
		 * array de objetos do tipo System_DB_Table_Row
		 * @var array
		 */
		private $_rows = array();

		/**
		 * parametros passados pelo usuario
		 * @var [type]
		 */
		private $_params;

		/**
		 * ponteiro ao arquivo
		 * @var [type]
		 */
		private $_handle = null;

		public function __construct() 
		{
			//$this->setFileName('WEB_ESTADO.TXT');
		}

		public function __destruct() 
		{
			if($this->_handle)
				fclose($this->_handle);
		}

		public static function initialize($className,$params=null) 
		{

			$obj = new System_Object_CSV;
			$obj->setFileName($params['fileName']);

			return $obj;
		}

		/**
		 * le o arquivo csv
		 * @return [type] [description]
		 */
		public function read($currRow=null,$rowsToGet=nulll) 
		{
			/**
			 * testa se foram passados parametros para filtrar a seleção
			 */
			if(is_numeric($currRow))
				$this->_currRow =  $currRow;
			if(is_numeric($rowsToGet))
				$this->_rowsToGet = $rowsToGet;

			// echo $this->_rowsToGet.'<br>';
			// echo $this->_currRow.'<br>';

			if(!$this->setHandle(fopen ($this->getFileName(),"r")))
				trigger_error("Não foi possível abrir o arquivo ". $this->getFileName());

			$handle = $this->getHandle();
			$separator = $this->getSeparator();

			$rowObj = new System_DB_Table_Row;
			$rowObj->setTableName($this->getFileName());

			$rowCounter = 0;
			$header = "";
			while (($data = fgetcsv($handle, 1000, $separator)) !== FALSE) {

				/**
				 * salva o cabeçalho diferenciadamente
				 */
				if(!$rowCounter) {
					$header = $data;
				}else {
					/**
					 *  só salva no array se a linha atual for maior ou igual a 
					 *  currRow (linha atual)
					 */
					//echo $rowCounter.'>='.$this->_currRow.'<br>';
					if($rowCounter >= $this->_currRow) {
						
						//echo 'entrou<br>';

						$row = $rowObj->setRow($header,$data);
						$row->setTableName($this->getFileName());

						/**
						 * adiciona o objeto linha ao array de retorno
						 */
						$this->_rows[$rowCounter] = $row;

						/**
						 * se counter chegar a rowsToGet retorna rows
						 * @var [type]
						 */
						if(($rowCounter == ($this->_rowsToGet+$this->_currRow))) {
							//echo "Múltiplo e saindo: ".$rowCounter." ".$this->_rowsToGet.'<br>';
							return $this->_rows;
						}
							

					}
				}
				$rowCounter++;
			}
			return $this->_rows;
		}

		public function run() 
		{

		}

		//----------------GETTERS & SETTERS ----------------
		/**
		 * metodo para ser sobreescrito pela classe filha
		 */
		public function setParams($params) 
		{

			$this->_params = $params;
		}

		/**
		 * pega um parametro
		 * @param  [type] $param [description]
		 * @return [type]        [description]
		 */
		public function getParam($param) 
		{

			return $this->_params[$param];
		}

		public function getFileName()
		{
		    return $this->_fileName;
		}
		
		public function setFileName($fileName)
		{
		    $this->_fileName = $fileName;
		}
		
		public function &getHandle()
		{
			return $this->_handle;
		}
		
		public function setHandle($handle)
		{
			$this->_handle = $handle;
			return $this->_handle;
		}
		
		public function &getSeparator()
		{
		    return $this->_separator;
		}
		
		public function setSeparator($separator)
		{
		    $this->_separator = $separator;
		}
		
		//----------------FIM GETTERS & SETTERS ----------------
	}
?>