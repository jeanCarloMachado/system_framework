<?php 
	class System_Object_Date_Year
	{
		private $_val;

		public function __construct($val)
		{
			$this->setVal($val);
		}

		public function getVal()
		{
		    return $this->_val;
		}
		
		public function setVal($val)
		{
		    $this->_val = $val;
		}		
	}
?>