<?php
	/**
	 * sobreescreve o dbtable default com um customizado para o ack
	 * com observers setados de log
	 */
	abstract class Reuse_ACK_System_DB_Table extends System_DB_Table
	{
	
	
		public function __construct() 
		{
			parent::__construct();

			$this->attach("Reuse_ACK_Model_Log");
		}

	}
?>
