<?php

	/**
	 * CLASSE CATEGORIA
	 */
	class Reuse_ACK_Model_Categorias extends System_Model
	{
		protected $_name = 'categorias';

		/**
		 * PEGA TODOS OS FILHOS DE PRODUTO CCOM ALGUMA CLAUSULA WHERE
		 * 
		 * @param  array $where=null array que formara a clausula where 
		 * @param  array $params     array de atributos adicionais a consulta
		 * @return array             retona um array com toda a árvore de filhos da categoria
		 */
		public function getTree($where=null,$params=null,$module)
		{
			$where['status'] = 1;
			//$where['visivel'] = 1;

			$this->setTableName('categorias');

			$tabelaPai['relationCollumn'][0]  = 'id';
			$tabelaPai['whereClausule'] = $where;
			//$tabelaPai['addParam']['order'] = true;
			//$tabelaPai['addParam']['limit']['max'] = $limit;		
			
			//PEGA AS FOTOS
			$tabelasFilhas[0]['name']  = 'fotos';
			$tabelasFilhas[0]['relationCollumn'][0] = 'relacao_id';
			$tabelasFilhas[0]['whereClausule'] = array('modulo'=>$module,'status'=>1,'visivel_'.System_Language::current() =>'1');
			
		
			//PEGA OS VIDEOS
			$tabelasFilhas[1]['name']  = 'videos';
			$tabelasFilhas[1]['relationCollumn'][0] = 'relacao_id';
			$tabelasFilhas[1]['whereClausule'] = array('modulo'=>$module);

			//PEGA OS ANEXOS
			$tabelasFilhas[2]['name']  = 'anexos';
			$tabelasFilhas[2]['relationCollumn'][0] = 'relacao_id';
			$tabelasFilhas[2]['whereClausule'] = array('modulo'=>$module);

			/*//PEGA INSTITUCIONAL
			$tabelasFilhas[3]['name']  = 'institucional';
			$tabelasFilhas[3]['relationCollumn'][0] = 'categoria';
			$tabelasFilhas[3]['whereClausule'] = array('modulo'=>$module);*/

			$result = $this->getRelation($tabelaPai,$tabelasFilhas);

			$institucional = new Reuse_ACK_Model_Institucional;
			/**
			 * PEGA TODA A ARVORE DE CADA INSTITUCIONAL
			 */
			foreach($result as $categoriaId => $categoria)
			{	
				$result[$categoriaId]['institucional'] = $institucional->getTree(array('categoria'=>$categoria['id'],'visivel'=>'1','modulo'=>$module),null,$module);
			}
			return $result;
		}

		public function get($where=null,$params=null)
        {
            return $this->ioGet($where,$params);
        }

    	public function ioGet($array,$params=null)
        {
        	$array['status'] = 1;

            return parent::ioGet($array,$params);
        }

	}
?>