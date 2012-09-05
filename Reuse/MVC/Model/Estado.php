<?php

	class Reuse_MVC_Model_Estado extends System_Model
	{
		protected $_name = "estados_ikro";
		private $cidades;

		public function get($where=null,$params=null)
		{
			$result = $this->ioGet($where,$params);
			return $result;
		}


		public function updateOrCreate($set,$where,$params=null) {

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
		public function getTree($where=null,$params=null,$module=null)
		{
			$result = $this->get($where,$params);

			$this->cidades = new Reuse_MVC_Model_Cidade;

			foreach($result as $estadoId => $estado) 
			{				
				$result[$estadoId]['cidade'] = $this->cidades->get(array('estados_id'=>$estado['id']));
			}
			
			return $result;
		}
	}
?>