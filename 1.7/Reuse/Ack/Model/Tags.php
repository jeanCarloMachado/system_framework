<?php
	//namespace System;

	class Tags extends System_Db_Table_Abstract
	{
		protected $_name = "img_tags";
		protected $_row = "Tag";
		const moduleName = "tags";	
		const moduleId = 28;
		
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