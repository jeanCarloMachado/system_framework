<?php
	//namespace System;
	
	/**
	 * usar como modelo
	 * @author jean
	 *
	 */
	class Reuse_Ack_Model_Institutionals extends System_Db_Table_Abstract
	{
		protected $_name = "institucional";
		protected $_row = "Reuse_Ack_Model_Institutional";
		
		public $elementSingular = "institucional";
		public $elementPlural = "institucionais";
		
		
		const moduleName = "institucional";	
		const moduleId = 7;
	}
?>