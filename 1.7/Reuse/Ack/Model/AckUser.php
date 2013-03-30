<?php
	//namespace System;

	class Reuse_Ack_Model_AckUser extends System_Db_Table_AbstractRow
	{
		protected $_table = "Reuse_Ack_Model_AckUsers";
		
		public function permissonLevelOfModule($moduleId)
		{
			if(!$moduleId)
				throw new Excpetion("o id do módulo é obrigatório");
			
			$modelPermission = new Reuse_Ack_Model_Permissions;
			
			$where = array("usuario"=>$this->getId()->getBruteVal(),"modulo"=>$moduleId);
			$result = $modelPermission->toObject()->get($where);
			$result = reset($result);
			
			if(empty($result))
				return 0;
			
			return $result->getNivel()->getBruteVal();
		}
		
		/**
		 * reseta a senha do usuário
		 * @return Ambigous <string>
		 */
		public function resetPassword()
		{
			$set = array();
			$generated = System_Object_Password::sgenerate();
				
			{
				$modelTableName = $this->_table;
				$modelUsers = new $modelTableName;
				$passCol = $modelUsers->passwordColumn;
				$set[$passCol] = $generated["password"];
			}
				
			$where = array("id"=>$this->getId()->getBruteVal());
		
			$model = new Users;
			$result = $model->update($set,
					$where);
				
			return $generated["password"];
		}
	}
?>