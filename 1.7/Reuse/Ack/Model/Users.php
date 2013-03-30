<?php
	//namespace System;

	class Users extends Reuse_Ack_Model_AckUsers
	{
		protected $_name = "img_users";
		protected $_row = "User";
		
		const moduleName = "imguser";	
		const moduleId = 21;
		
		protected $elementSingular = "Usuário da IMGStock";
		protected $elementPlural = "Usuários da IMGStock";
		
		public  $identityColumn = "email";
		public  $passwordColumn = "password";
		public  $inclusionDateColumn = "date_inclusion";
		
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
		
		public function create(array $set)
		{
			
			if(empty($set["nick_fantasy"]) && !empty($set["socialreason_completename"]))
				$set["nick_fantasy"] = $set["socialreason_completename"];
			
			return parent::create($set);
		}
	}
?>