<?php

	class Reuse_Pacman_Model_Usuario extends System_Db_Table_AbstractRow
	{
		protected $_table = "Reuse_Pacman_Model_Usuarios";
		
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
		
		public function getMeusEnderecos(){
			$id = $this->getId()->getBruteVal();
			if(empty($id))
				return null;
			
			$enderecosModel = new Reuse_Pacman_Model_UsuariosEnderecos;
			$result = $enderecosModel->getEnderecosByUsuariosId($id);
			return $result;
		}
	}
?>