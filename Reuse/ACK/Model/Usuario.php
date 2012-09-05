<?php
	class Reuse_ACK_Model_Usuario extends System_DB_Table
	{
		protected $_name = "usuarios_ikro";
		protected $_passwordColumn = "senha_responsavel";
		protected $_login = "desp_cod";
		
		
		/**
		 * sobreescreve a  função de criar usuário
		 * @param  [type] $array [description]
		 * @return [type]        [description]
		 */
		public function create($array)
		{

			if(!array_key_exists($this->_passwordColumn, $array)) {

				$password = new System_Auth_Password;
				$newPassword  = $password->generate();
				$array[$this->_passwordColumn] = $newPassword['encrypt'];
			}

			parent::create($array);
		}

		/**
		 * dá update no password
		 * @param  [type] $password    [description]
		 * @param  [type] $where       [description]
		 * @param  [type] $params=null [description]
		 * @return [type]              [description]
		 */
		public function updatePassword($password,$where,$params=null)
		{
			$set[$this->_passwordColumn] = $password;
			$result = $this->update($set,$where);
					
			return $result;
		}


		public function updateUser($setOuter,$whereOuter,$params=null)
		{	
			//dg($setOuter);
			if(array_key_exists('senha_responsavel',$setOuter))
				$setOuter['senha_responsavel'] = md5($setOuter['senha_responsavel']);

			/**
			 * insere o pais
			 * @var Pais
			 */
			$pais = new Reuse_MVC_Model_Pais;
			$set = array('nome'=> $setOuter['pais']);
			$where = array('nome'=> $setOuter['pais']);			
			$paisId = $pais->updateOrCreate($set,$where,null);
			/**
			 * insere o estado 
			 */
			$estado = new Reuse_MVC_Model_Estado;
			$set = array('sigla'=> $setOuter['uf'],
						'pais_id'=>$paisId);
			$where = array('sigla'=> $setOuter['uf'],
						'pais_id'=>$paisId);
			$estadoId = $estado->updateOrCreate($set,$where,null);
			/**
			 * insere a cidade
			 */
			$cidade = new Reuse_MVC_Model_Cidade;
			$set = array('nome'=> $setOuter['cidade'],
						 'cep' => $setOuter['cep'],
						'estados_id'=>$estadoId);

			$where = array('nome'=> $setOuter['cidade'],
						'estados_id'=>$estadoId);

			$cidadeId = $cidade->updateOrCreate($set,$where,null);

			/**
			 * insere o usuario
			 */
			$setOuter['cidade_id'] = $cidadeId;
			$result = $this->update($setOuter,$whereOuter);
					
			return $result;
		}
	}
?>