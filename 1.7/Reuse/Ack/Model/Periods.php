<?php

	class Periods extends System_Db_Table_Abstract
	{
		protected $_name = "img_periods";
		protected $_row = "Period";
		
		const moduleId = 37;
		const moduleName = "img_periods";
		
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
				),
				
				"order" => "ordem asc"
		);
	}
?>