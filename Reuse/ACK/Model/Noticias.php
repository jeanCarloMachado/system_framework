<?php

	class Reuse_ACK_Model_Noticias extends System_DB_Table
	{

		private $module = 2;
		protected $_name = "noticias";
			
		protected $_dependentTables = array('Reuse_ACK_Model_Fotos','Reuse_ACK_Model_Videos','Reuse_ACK_Model_Anexos');



		//PEGA TODOS OS FILHOS DE PRODUTO CCOM ALGUMA CLAUSULA WHERE
		// public function getTree($where=array(),$limit=1)
		// {
		// 	$this->setTableName('noticias');

		// 	$tabelaPai['relationCollumn'][0]  = 'id';
		// 	$tabelaPai['relationCollumn'][2]  = 'linha_id';
		// 	$tabelaPai['whereClausule'] = $where;
		// 	$tabelaPai['addParam']['order'] = true;
		// 	$tabelaPai['addParam']['limit']['max'] = $limit;

		// 	//PEGA AS FOTOS
		// 	$tabelasFilhas[0]['name']  = 'fotos';
		// 	$tabelasFilhas[0]['relationCollumn'][0] = 'relacao_id';
		// 	$tabelasFilhas[0]['whereClausule'] = array('modulo'=>$this->module);

		// 	//PEGA OS VIDEOS
		// 	$tabelasFilhas[1]['name']  = 'videos';
		// 	$tabelasFilhas[1]['relationCollumn'][0] = 'relacao_id';
		// 	$tabelasFilhas[1]['whereClausule'] = array('modulo'=>$this->module);

		// 	//PEGA OS ANEXOS
		// 	$tabelasFilhas[2]['name']  = 'anexos';
		// 	$tabelasFilhas[2]['relationCollumn'][0] = 'relacao_id';
		// 	$tabelasFilhas[2]['whereClausule'] = array('modulo'=>$this->module);

		// 	$result = $this->ioGetWithRelation($tabelaPai,$tabelasFilhas);

		// 	return $result;
		// }

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