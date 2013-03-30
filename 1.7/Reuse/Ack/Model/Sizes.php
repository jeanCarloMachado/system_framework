<?php
	//namespace System;

	class Sizes extends System_Db_Table_Abstract
	{
		protected $_name = "img_sizes";
		protected $_row = "Size";
		
		const moduleName = "imgsizes";
		const moduleId = 40;
			
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
			
		public function update(array $set,$where)
		{
			if($set["credits"])
				$set["credits"] = System_Object_Number::fromBrazil($set["credits"]);
		
			return parent::update($set,$where);
		}
		public function create(array $set)
		{
			$set["credits"] = System_Object_Number::fromBrazil($set["credits"]);
			return parent::create($set);
		}
	}
?>