<?php

	class Reuse_Base_Model_State extends System_DB_Table
	{
		protected $_name = "app_states";
		private $cidades;


		public function updateOrCreate(array $set,array $where,$params=null)
		{

			$result = $this->ioUpdateOrCreate($set,$where,$params);

			return $result;
		}

		/**
		 * retorna a arvore de elementos (estados e cidades)
		 * @param  [type] $where=null  [description]
		 * @param  [type] $params=null [description]
		 * @param  [type] $module      [description]
		 * @return [type]              [description]
		 */
		public function getTree(array $array=null,$params=null,$columns=null)
		{
			$result = $this->get($array,$params);

			$this->cidades = new Reuse_Base_Model_City;

			foreach($result as $estadoId => $estado) 
			{				
				$result[$estadoId]['cidade'] = $this->cidades->get(array('estados_id'=>$estado['id']));
			}
			
			return $result;
		}
	}
?>