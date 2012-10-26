<?php

	class Videos extends Model
	{

		private $module = 2;
		protected $_name = "videos";

		/*	
			NORMALMENTE SE CHAMA FOTOS A PARTIR DE UM OUTRO MODEL
				
		PEGA TODOS OS FILHOS DE PRODUTO CCOM ALGUMA CLAUSULA WHERE
		public function getTree($where=array(),$limit=1)
		{
			$this->setTableName('noticias');

			$tabelaPai['relationCollumn'][0]  = 'id';
			$tabelaPai['relationCollumn'][2]  = 'linha_id';
			$tabelaPai['whereClausule'] = $where;
			$tabelaPai['addParam']['order'] = true;
			$tabelaPai['addParam']['limit']['max'] = $limit;

			//PEGA AS FOTOS
			$tabelasFilhas[0]['name']  = 'fotos';
			$tabelasFilhas[0]['relationCollumn'][0] = 'relacao_id';
			$tabelasFilhas[0]['whereClausule'] = array('modulo'=>$this->module);

			//PEGA OS VIDEOS
			$tabelasFilhas[1]['name']  = 'videos';
			$tabelasFilhas[1]['relationCollumn'][0] = 'relacao_id';
			$tabelasFilhas[1]['whereClausule'] = array('modulo'=>$this->module);


			$result = $this->ioGetWithRelation($tabelaPai,$tabelasFilhas);

			return $result;
		}

		 */
	}
?>