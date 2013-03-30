<?php

	class Reuse_Pacman_Model_UsuariosEnderecos extends System_Db_Table_Abstract
	{
		protected $_name = "sys_usuarios_has_sys_enderecos";
		//protected $_row = "Reuse_Pacman_Model_Usuario";
		
		
		public function getEnderecosByUsuariosId($id)
		{
			$modelEnderecos = new Reuse_Pacman_Model_Enderecos;
			
			$enderecosId = $this->get(array("usuario_id" => $id));
			$result = array();
			foreach($enderecosId as $enderecos){
				
				$result[] = $modelEnderecos->toObject()->getOne(array("status"=>1, "id"=>$enderecos['endereco_id']));
			}
			
			return $result;
		}
		
	}
