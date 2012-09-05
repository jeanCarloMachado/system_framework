<?php

	class Reuse_ACK_Model_Institucional extends System_DB_Table
	{
		protected $_name = "institucional";

		protected $_dependentTables = array('Reuse_ACK_Model_Fotos');


		public function getTree($array,$params=null,$columns=null) {

			//$array['visivel'] = 1;

			return parent::getTree($array,$params,$columns);
		}

		public function get($array,$params=null,$columns=null)
		{
			$array['status'] = 1;
			//$array['visivel'] = 1;

			return parent::get($array,$params,$columns);
		}

		public function count($where = null) {
			$where['status'] = 1;
			return parent::count($where);
		}

	}	
?>