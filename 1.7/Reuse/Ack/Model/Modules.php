<?php
	//namespace System;

	class Reuse_Ack_Model_Modules extends System_Db_Table_Abstract
	{
		protected $_name = "modulos";
		protected $_row = "Reuse_Ack_Model_Module";
		const moduleName = "modulos";	
		const moduleId = 5;
		const termosId = 32;
		const politicaId = 31;
		const cadastrefotografoId = 33;
		
		protected $elementSingular = "Módulo";
		protected $elementPlural = "Módulos";
		
	}
?>