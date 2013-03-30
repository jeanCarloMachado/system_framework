<?php

	class Policies extends System_Db_Table_Abstract
	{
		protected $_name = "img_policities";
		protected $_row = "Policy";
		
		const moduleId = 35;
		const moduleName = "img_licenses";
		
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