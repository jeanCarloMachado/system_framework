<?php
	class Reuse_MVC_Model_Usuario extends System_DB_Table implements System_DesignPattern_Observer_Interface
	{
		protected $_name = "usuarios_ikro";
		protected $_passwordColumn = "senha_responsavel";
		protected $_login = "desp_cod";
		
		/**
		 * array de objetos observadores
		 * @var array
		 */
		private $_observers = array();	
		
		/**
		 * sobreescreve a  função de criar usuário
		 * @param  [type] $array [description]
		 * @return [type]        [description]
		 */
		public function ioCreate($array)
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

		/**
		 * pega um usuario pelo id
		 * @param  [type] $id [description]
		 * @return [type]     [description]
		 */
		public function getById($id)
		{
			return $this->get(array('id'=>$id));
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
		
		/**
		 * adiciona um objeto aos observadores
		 * @param  [type] $obj [description]
		 * @return [type]      [description]
		 */
		public function attach($objName)
		{
			$this->_observers[] = $objName;
		}

		/**
		 * remove um objeto pela chave
		 * @param  [type] $obj [description]
		 * @return [type]      [description]
		 */
		public function detach($objName)
		{
			foreach($this->_observers as $observerId => $observer) {
				if($objName == $observer) {
					unset($this->_observers[$observerId]);
					return true;
				}
			}
			return false;
		}

		/**
		 * notifica os observadores
		 * @param  [type] $message [description]
		 * @return [type]          [description]
		 */
		public function notify($message)
		{
			foreach($this->_observers as $observerName) {
				$observer = new $observerName;
				$observer->listen($message);
			}
		}
	}
?>