<?php
	//namespace System;

	class User extends Reuse_Ack_Model_AckUser
	{
		protected $_table = "Users";
		
		/**
		 * pega o usuário administrador do usuário
		 * atual, caso não exita retorna um objeto em branco
		 * @return User
		 */
		public function getMyAdmin()
		{
			if($this->getAdmin()->getBruteVal() || !$this->getId()->getBruteVal())
				return new User;
			
			$modelHierarchys = new UserHierarchys();
			$relation = $modelHierarchys->toObject()->get(array("slave_id"=>$this->getId()->getBruteVal()));
			
			$relation = reset($relation);
			
			if(empty($relation))
				return new User;
			
			
			$modelUsers = new Users;
			$result = $modelUsers->toObject()->onlyNotDeleted()->get(array("id"=>$relation->getMasterId()->getBruteVal()));
			
			$result = reset($result);
			
			if(empty($result))
				return new User;

			return $result;
		}
		
		public function getMyFirstPhotoObj()
		{
			$modelName = $this->getTableModelName();
			
			$modelPhotos = new Photos();
			$where = array("relacao_id"=>$this->getId()->getBruteVal(),"modulo"=>$modelName::moduleId);
			
			$result = $modelPhotos->onlyAvailable()->toObject()->get($where);
			
			if(empty($result))
				return null;
			
			$result = reset($result);
			
			return $result;			
		}
		
		public function getMySlavesRelations() 
		{
			if(!$this->getId()->getBruteVal())
				return null;

			
			$result = null;
			
			$modelHierarchys = new UserHierarchys();
			$result = $modelHierarchys->toObject()->get(array("master_id"=>$this->getId()->getBruteVal()));
				
			
			if(empty($result))
				return null;
			
			return $result;
				
		}
	}
?>