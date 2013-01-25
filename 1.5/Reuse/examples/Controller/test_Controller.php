<?php
	System_Autoloader::simpleTest();
	System_Autoloader::setVersion("1.5");
	
	
	class test_Controller extends System_Controller
	{
		private $_tests = array('PedidosTest');
		//private $_tests = array('ScheduleTest','CommentTest','BudgetTest','UserTest', 'PermissionTest','ContactTest');

		public function index() 
		{
			foreach($this->_tests as $element) {
				$obj = new $element();
			}
		}
	}
?>