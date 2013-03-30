<?php
	/**
	 * sobreescreve o dbtable default com um customizado para o ack
	 * com observers setados de log
	 */
	abstract class Reuse_ACK_System_DB_Table extends System_DB_Table
	{	
		/**
		 * filtros básicos do ack
		 * (não tem obrigatoriedade de serem usados)
		 * para ativar utilizar $this->useFilter()->get(..
		 * @var array
		 */
		protected $_filter = array('status'=>1,'visivel'=>1);

		public function __construct() 
		{
			parent::__construct();

			$this->attach("Reuse_ACK_Model_Log");
		}
	
		
	}
?>
