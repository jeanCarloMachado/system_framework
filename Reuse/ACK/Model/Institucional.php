<?php

	class Reuse_ACK_Model_Institucional extends System_DB_Table
	{
		protected $_name = "institucional";

		protected $_dependentTables = array('Reuse_ACK_Model_Fotos','Reuse_ACK_Model_Videos','Reuse_ACK_Model_Anexos');

		public function getTree($array,$params=null,$columns=null) {


			$result = parent::getTree($array,$params,$columns);

			foreach($result as $elementId => $element) {

				foreach($element['fotos'] as $fotoId => $foto) {
					if($foto['status'] != 1 && $foto['visivel_'.System_Language::current()] != '1') {
						unset($result[$elementId]['fotos'][$fotoId]);
					}
				}

				foreach($element['anexos'] as $anexoId => $anexo) {
					if($anexo['status'] != 1 && $anexo['visivel_'.System_Language::current()] != '1') {
						unset($result[$elementId]['anexos'][$anexoId]);
					}
				}
			}

			return $result;
		}

		public function get($array,$params=null,$columns=null)
		{
			$array['status'] = 1;
			//$array['visivel'] = 1;

			return parent::get($array,$params,$columns);
		}

		public function count($where = null) {
			$where['status'] = 1;
			//$where['visivel'] = 1;
			return parent::count($where);
		}

	}	
?>