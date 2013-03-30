<?php

	class Regions extends System_Db_Table_Abstract
	{
		protected $_name = "img_regions";
		protected $_row = "Region";
		
		const moduleId = 36;
		const moduleName = "img_regions";
		
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