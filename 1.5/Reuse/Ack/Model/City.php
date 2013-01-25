<?php
	class Reuse_Ack_Model_City extends System_Db_Table_AbstractRow
	{
		protected $_table = "Reuse_AcK_Model_Cities";


		
		public static function Factory(&$params=null)
		{
			$extraArgs = func_get_args();
			/**
			 * pega o nome da classe
			 * pois não há linkagem atrada em php's < 3.5
			 * @var [type]
			 */
			$rowClass = ($extraArgs[1]);

			$result = null;

			if(is_array($params)) {

				$result = array();

				foreach($params as $elementId => $element) {
					$result[] = new $rowClass($element);
				}
			}

			return $result;
		}
	}
?>	 	