<?php
	//namespace System;

	class CreditPlans extends System_Db_Table_Abstract
	{
		protected $_name = "img_credit_plans";
		protected $_row = "CreditPlan";
		protected $elementSingular = "Plano";
		protected $elementPlural = "Planos";
		
		const moduleName = "creditplans";	
		const moduleId = 23;
	
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
			if($set["price_total"])
				$set["price_total"] = System_Object_Number::fromBrazil($set["price_total"]);
		
			return parent::update($set,$where);
		}
		
		public function create(array $set)
		{
			$set["price_total"] = System_Object_Number::fromBrazil($set["price_total"]);
			
			return parent::create($set);
		}
	}
?>