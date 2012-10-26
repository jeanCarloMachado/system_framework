<?php

	error_reporting(E_ALL);
	require_once 'System/Model.php';
	require_once 'Reuse/MVC/Model/Estado.php';

	class Reuse_MVC_Model_Pais extends Model
	{
		protected $_name = "pais_ikro";
		private $estados;

		public function get($where=null,$params=null)
		{
			$result = $this->ioGet($where,$params);
			return $result;
		}

		/**
		 * retorna a arvore de elementos (pais estados e cidades)
		 * @param  [type] $where=null  [description]
		 * @param  [type] $params=null [description]
		 * @param  [type] $module      [description]
		 * @return [type]              [description]
		 */
		public function getTree($where=null,$params=null,$module=null)
		{
			$result = $this->get($where,$params);

			/**
			 * Instancia de estado
			 * @var Estado
			 */
			$this->estados = new Reuse_MVC_Model_Estado;
			

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
			$this->estados = new Reuse_MVC_Model_Estado;
			

			foreach($result as $paisId => $pais) 
			{				
				$result[$paisId]['estado'] = $this->estados->get(array('pais_id'=>$pais['id']));
			}
			return $result;
		}


	}
?>