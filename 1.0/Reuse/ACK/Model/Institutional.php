<?php
	

	class Reuse_ACK_Model_Institutional extends System_DB_Table
	{
		protected $_name = "institucional";

		protected $_dependentTables = array('Reuse_ACK_Model_Image','Reuse_ACK_Model_Video','Reuse_ACK_Model_Annex');

		public function getTree(array $array,$params=null,$columns=null) 
		{

			$result = parent::getTree($array,$params,$columns);

			$arr['result'] = &$result;
			$arr['params'] = $params;	

			$result = System_Helper_ChildSelector::run($arr);

			return $result;
		}

		public function get(array $where,$params=null,$columns=null)
		{
			$where['status'] = 1;

			return parent::get($where,$params,$columns);
		}

		public function count($where = null) 
		{
			$where['status'] = 1;
			//$where['visivel'] = 1;
			return parent::count($where);
		}

	}	
?>