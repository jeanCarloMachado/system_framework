<?php

	class Reuse_Pacman_Model_Usuarios extends System_Db_Table_Abstract
	{
		protected $_name = "sys_usuarios";
		protected $_row = "Reuse_Pacman_Model_Usuario";
		
		//colunas utilizadas nas cl�usulas
		protected $identityColumn = "email";
		protected $passwordColumn = "senha";
		protected $inclusionDateColumn = "data_inclusao";
		protected $minPasswordSize = 3;

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
			
			if(empty($set[$this->passwordColumn]))
				unset($set[$this->passwordColumn]);
			else
				$set[$this->passwordColumn] = System_Object_Encryption::encrypt($set[$this->passwordColumn]);
			
			return parent::update($set,$where);
		}
		
		
	}
?>