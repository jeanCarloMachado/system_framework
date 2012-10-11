<?php

	require_once 'Test/Simple/unit_tester.php';
	require_once 'Test/Simple/reporter.php';

	class System_Test extends UnitTestCase 
	{
		protected $_reporter;

		/**
		 * sobreescreve o run para ativar o html reporter
		 * @return [type] [description]
		 */
		public function run($reporter) 
		{

			if(is_null($reporter))
				$this->_reporter = new HtmlReporter;
			else
				$this->_reporter = $reporter;

			parent::run($this->_reporter);
		}
	}
?>