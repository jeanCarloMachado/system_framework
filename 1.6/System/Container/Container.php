<?php
	/**
	 *  responsável por fazer a realização de pendendencia das classes
	 */
	class System_Container implements System_Container_Interface,System_DesignPattern_Singleton_Interface
	{	
		
		private static $instance;
		
		private $currRow;
		
		private function __construct()
		{
			
		}
		
		/**
		 * singleton ainda não foi totalmente implementado aqui
		 * @return [type] [description]
		*/
		public static function getInstance()
		{
			if(!self::$instance)
				self::$instance = new System_Container();
			
			return self::$instance;
		}

		
		public function getLanguageSuffixes()
		{
			return array("pt","en","es");
		}
		
		public function setCurrentRow(System_Db_Table_AbstractRow $row)
		{
			$this->currRow = $row;
				
			return $this;
				
		}
		
		public function getCurrentRow()
		{
			if(empty($this->currRow))
				throw new Exception("Linha atual não foi definida");
			
			return $this->currRow;
		}
	}
?>