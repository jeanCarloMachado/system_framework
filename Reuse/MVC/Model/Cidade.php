<?php
	class Reuse_MVC_Model_Cidade extends System_Model
	{
		private $estado;
		private $pais;

		protected $_name = "cidades_ikro";
		
		public function get($where=null,$params=null)
		{
			$result = $this->ioGet($where,$params);
			return $result;
		}

		/**
		 * pega cidade, estado e pais de
		 * @return [type] [description]
		 */
		public function getParents($where=null,$params=null) {

			$result= $this->get($where,$params);

			if(isset($result[0])) {	

				$this->estado = new Reuse_MVC_Model_Estado;
				$this->pais = new Reuse_MVC_Model_Pais;
					
				foreach($result as $columnId => $cidade) {

					$estadoResult = $this->estado->get(array('id' => $cidade['estados_id']));
					$paisResult = $this->pais->get(array('id'=>$estadoResult[0]['pais_id']));

					$result[$columnId]['estado'] = $estadoResult[0]['sigla'];
					$result[$columnId]['pais'] = $paisResult[0]['nome'];
				}
				return $result;
			}
			return false;
		}
	}
?>