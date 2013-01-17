<?php

	class Reuse_Base_Model_Country extends System_DB_Table
	{
		protected $_name = "app_countries";
		private $estados;

	
		/**
		 * retorna a arvore de elementos (pais estados e cidades)
		 * @param  [type] $where=null  [description]
		 * @param  [type] $params=null [description]
		 * @param  [type] $module      [description]
		 * @return [type]              [description]
		 */
		public function getTree(array $array=null,$params=null,$columns=null)
		{
			$result = $this->get($where,$params);

			/**
			 * Instancia de estado
			 * @var Estado
			 */
			
			$this->estados = new Reuse_Base_Model_State;
			

			foreach($result as $paisId => $pais) 
			{				
				$result[$paisId]['estado'] = $this->estados->getTree(array('pais_id'=>$pais['id']));
			}
			return $result;
		}

		public function getEstados($where=null,$params=null,$module=null)
		{
			
			$result = $this->get($where,$params);

			/**
			 * Instancia de estado
			 * @var Estado
			 */
			$this->estados = new Reuse_Base_Model_State;
			

			foreach($result as $paisId => $pais) 
			{				
				$result[$paisId]['estado'] = $this->estados->get(array('pais_id'=>$pais['id']));
			}
			return $result;
		}


	}
?>