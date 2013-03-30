<?php
	class Reuse_Ack_Model_Country extends System_Db_Table_AbstractRow
	{
		protected $_table = "Reuse_Ack_Model_Countrys";



		public static function Factory(&$params=null)
		{
			$extraArgs = func_get_args();
			/**
			 * pega o nome da classe
			 * pois não há linkagem atrada em php's < 3.5
			 * @var [type]
			 */
			$rowClass = ($extraArgs[1]);

			$result = null;

			if(is_array($params)) {

				$result = array();

				foreach($params as $elementId => $element) {
					$result[] = new $rowClass($element);
				}
			}

			return $result;
		}

	/**
	 * retorna as cidades do pais por uma cláusula de onde comprar
	 * @param  [type] $ondeComprarArr [description]
	 * @return [type]                 [description]
	 */
		private $statesByOndeComprarIds = null;
		

		public function getStatesByCitiesIds($citiesIdsArr,$where = array()) 
		{	
			if(empty($citiesIdsArr)) {
				throw new Exception("Necessário passar array com ids de cidades");
			}

			if(!empty($this->statesByOndeComprarIds))
				return $this->statesByOndeComprarIds;
			
			$cities = new Reuse_ACK_Model_Cities;
			$query = ("SELECT estados_id FROM ".$cities->getTableName());

		/**
		 * faz o where para pegar os estados se forem passados id de cidades
		 */
			$where = " WHERE";

			foreach($citiesIdsArr as $city) {

				if(is_array($city)) 
					$where.= " id=".$city["cidade_id"]." OR";
				else 
					$where.= " id=".$city." OR";
			}

			$where = substr($where, 0 ,- 3);
			$query.= $where;

			/**
			 * roda a consulta em cidades
			 * @var [type]
			 */
			$resultStatesIds = $cities->run($query);

			if(empty($resultStatesIds)) 
				return null;

		/**
		 * consulta os estados
		 * @var Reuse_ACK_Model_States
		 */
			$states = new Reuse_ACK_Model_States;
			
			$query = "SELECT * FROM ".$states->getTableName()." WHERE ";

			foreach($resultStatesIds as $state) {

				$query.= " id = ".$state["estados_id"]." OR";
			}

			$query = substr($query, 0 ,- 3);
			$query.= " ORDER BY descricao ASC";

			$this->statesByOndeComprarIds = $states->toObject()->run($query);
			
			return $this->statesByOndeComprarIds;
		}

		/**
		 * pega os estados de acordo com a
		 * @param  array  $where [description]
		 * @return [type]        [description]
		 */
		public function getStates($where=array())
		{	
			$where["pais_id"] = $this->getId()->getValue();

			$states = new Reuse_Ack_Model_States;

			$result = $states->toObject()->get($where);
			return $result;
		}
	}
?>