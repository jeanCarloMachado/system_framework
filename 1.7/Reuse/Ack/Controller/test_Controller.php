<?php
	System_Autoloader::simpleTest();
	
	
	class test_Controller extends System_Controller
	{
		private $_tests = array("FaqTest","UserTest","PlansTest","ContatoTest","SectorsTest");
		//private $_tests = array('ScheduleTest','CommentTest','BudgetTest','UserTest', 'PermissionTest','ContactTest');

		public function index() 
		{
			foreach($this->_tests as $element) {
				$obj = new $element();
				die;
			}
		}
	}
?>