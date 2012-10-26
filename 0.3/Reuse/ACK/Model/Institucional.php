<?php

	class Institucional extends Model
	{
		protected $_name = "institucional";

		 /**
		     * Get genérico usado em todos os casos 
		     * em que não há peculiaridades
		     *
		     * @param  array
		     * @return array com elementos da tabela da classe
		     */
				/**
		     * Consulta no banco e retona ao usuario
		     * @var tipo
		 */	
		public function get($where)
		{
			$result = $this->ioGet($where);
			return $result;
		}

		 /**
		     * Pega somente as ultimas noticias
		     *
		     * @param  array
		     * @return array com elementos da tabela da classe
		     */
				/**
		     * Consulta no banco e retona ao usuario
		     * @var tipo
		 */	

		//PEGA TODOS OS FILHOS DE PRODUTO CCOM ALGUMA CLAUSULA WHERE
		public function getTree($where=null,$params,$module = 3)
		{
			$this->setTableName('institucional');

			$tabelaPai['relationCollumn'][0]  = 'id';
			$tabelaPai['whereClausule'] = $where;
			//$tabelaPai['addParam']['order'] = true;
			//$tabelaPai['addParam']['limit']['max'] = $limit;

			//PEGA INSTITUCIONAL

			//PEGA AS FOTOS
			$tabelasFilhas[0]['name']  = 'fotos';
			$tabelasFilhas[0]['relationCollumn'][0] = 'relacao_id';
			$tabelasFilhas[0]['whereClausule'] = array('modulo'=>$module);

			//PEGA OS VIDEOS
			$tabelasFilhas[1]['name']  = 'videos';
			$tabelasFilhas[1]['relationCollumn'][0] = 'relacao_id';
			$tabelasFilhas[1]['whereClausule'] = array('modulo'=>$module);

			//PEGA OS ANEXOS
			$tabelasFilhas[2]['name']  = 'anexos';
			$tabelasFilhas[2]['relationCollumn'][0] = 'relacao_id';
			$tabelasFilhas[2]['whereClausule'] = array('modulo'=>$module);

			$result = $this->getRelation($tabelaPai,$tabelasFilhas);

			return $result;
		}
	}	
?>