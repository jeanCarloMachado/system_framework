<?php
	//namespace System;

	class FaqCategorys extends System_Db_Table_Abstract
	{
		protected $_name = "img_faq_categorys";
		protected $_row = "FaqCategory";
		
 		const moduleName = "faq_category";
 		const moduleId = 29;
		
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