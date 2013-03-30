<?php
	//namespace System;

	class Faqs extends System_Db_Table_Abstract
	{
		protected $_name = "img_faq";
		protected $_row = "Faq";
		
		const moduleName = "faq";
		const moduleId = 22;
		
		public $elementSingular = "FAQ";
		public $elementPlural = "FAQ's";
		
		protected $functionColumns = array(
					
				//utilizado na função onlyAvailable e nos controladores do ack (status visível)
				"visible" => array (
						"name"=>"visible",
						"enabled"=>1,
						"disabled"=>0
				),
				//utilizado na função onlyAvailable e onlyNotDeleted
				"status" => array (
						"name"=>"status",
						"enabled"=>1,
						"disabled"=>0
				)
		);
	}
?>