<?php

	class Images extends System_Db_Table_Abstract
	{
		protected $_name = "img_images";
		protected $_row = "Image";
		
		const moduleName = "img_image";
		const moduleId = 34;
			
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
		
		public function update(array $set,$where)
		{
			if(!empty($set["validity"]))
				$set["validity"] = System_Object_Date::toMysql($set["validity"],"/");
			
			if(!empty($set["credits_base"]))
				$set["credits_base"] = System_Object_Number::fromBrazil($set["credits_base"]);
			
			return parent::update($set,$where);
		}
	}
?>