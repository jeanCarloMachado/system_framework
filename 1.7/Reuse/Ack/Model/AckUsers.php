<?php
	//namespace System;

	class Reuse_Ack_Model_AckUsers extends System_Db_Table_Abstract
	{
		protected $_name = "usuarios";
		protected $_row = "Reuse_Ack_Model_AckUser";
		protected $alias = "Usuário";
		const moduleId = 3;
		const moduleName = "usuarios_ack";
		
		protected $elementSingular = "Usuário";
		protected $elementPlural = "Usuários";
		
		public	$identityColumn = "email";
		public  $passwordColumn = "senha";
		public  $inclusionDateColumn = "dt_inc";
		protected $minPasswordSize = 3;

		/**
		 * colunas (default) usadas em funcionalidades do
		 	* sitema
		 * @var unknown
		 */
		protected $functionColumns = array(
				//utilizado na função onlyAvailable e onlyNotDeleted
				"status" => array (
						"name"=>"status",
						"enabled"=>"1",
						"disabled"=>"9"
				)
		);
		
		public function create(array $set)
		{
			if((!$set[$this->passwordColumn]) && !empty($set["senha"]))
				$set[$this->passwordColumn] = $set["senha"];
			
			if(empty($set[$this->identityColumn]) || empty($set[$this->passwordColumn]))
				throw new Exception("usuario ou senha não definidos");
				
			
			$resultUser = $this->get(array($this->identityColumn =>$set[$this->identityColumn]));
			
			if(!empty($resulUser))
				throw new Exception("usuário já existente");
			
			/**
			 * encripta a senha do usuario 
			 */
			$set[$this->passwordColumn] = System_Object_Encryption::encrypt($set[$this->passwordColumn]);
			/**
			 * seta a data de criacao
			 */
			$set[$this->inclusionDateColumn] = System_Object_Date::now();
			
			return parent::create($set);
		}
		
		public function update(array $set,$where)
		{
			if((!$set[$this->passwordColumn]) && !empty($set["senha"]))
				$set[$this->passwordColumn] = $set["senha"];
			
			if(strlen($set[$this->passwordColumn]) <= $minPasswordSize)
				unset($set[$this->passwordColumn]);
			else
				$set[$this->passwordColumn] = System_Object_Encryption::encrypt($set[$this->passwordColumn]);
			
			return parent::update($set,$where);
		}
	}
?>