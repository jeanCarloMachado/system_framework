<?php

	class Reuse_ACK_Model_News extends System_DB_Table
	{
		private $module = 2;
		protected $_name = "noticias";
			
		protected $_dependentTables = array('Reuse_ACK_Model_Image','Reuse_ACK_Model_Video','Reuse_ACK_Model_Annex');


		public function getTree($array,$params=null,$columns=null) 
		{
			$array['status'] = 1;
			$array['visivel'] = 1;


			$result = parent::getTree($array,$params,$columns);

			foreach($result as $elementId => $element) {

				foreach($element['fotos'] as $fotoId => $foto) {

					if($foto['status'] != 1 || $foto['visivel_'.System_Language::current()] != '1') {
						unset($result[$elementId]['fotos'][$fotoId]);
					}
				}

				foreach($element['anexos'] as $anexoId => $anexo) {
					if($anexo['status'] != 1 ||$anexo['visivel_'.System_Language::current()] != '1') {
						unset($result[$elementId]['anexos'][$anexoId]);
					}
				}

				$array = $result[$elementId]['fotos'];
				$result[$elementId]['fotos'] = array();
				foreach($array as $element) {
					array_push($result[$elementId]['fotos'], $element);
				}
			}

			return $result;
		}

		public function updateNoticias($set,$where)
		{	
			//dg($where);
			$result = $this->update($set,$where);			
			return $result;
		}

		//PEGA AS NOTICIAS MAIS ACESSADAS
		public function getMoreAcessed(	$start=0,$totalNum=1)
		{	
			$result = $this->ioGet($where,array('order'=>array('order'=>'DESC',
																'column'=>'acessos'),
												'limit'=>array('min'=>$start,
																'max'=>$totalNum)));
			return $result;
		}		
	}	
?>