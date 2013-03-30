<?php
	class Reuse_Base_Model_City extends System_DB_Table
	{
		private $estado;
		private $pais;

		protected $_name = "app_cities";
		

		/**
		 * pega cidade, estado e pais de
		 * @return [type] [description]
		 */
		public function getParents($where=null,$params=null) {

			$result= $this->get($where,$params);

			if(isset($result[0])) {	

				$this->estado = new Reuse_Base_Model_State;
				$this->pais = new Reuse_Base_Model_Country;
					
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