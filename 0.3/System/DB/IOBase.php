<?php
	require_once 'Table.php';
	class System_DB_IOBase extends System_DB_Table
	{

		public function __construct($_name)
		{
			$this->setTableName($_name);
			parent::__construct();
		}

		//insere uma linha na tabela especificada		
		public function ioCreate($array)
		{
			$this->insert();
			$this->values($array);	
			$result = $this->run();

			$myQuery = $this->getQuery();
			
			$result = $this->getLastId();
			$this->setQuery($myQuery);

			return $result;
		}
		 /**
		     * Get genérico usado em todos os casos 
		     * em que não há peculiaridades
		     * MODO DE USO:
		     *
		     * $this->ioGet($where,array('order'=>array('order'=>'DESC',
			 *													'column'=>'acessos'),
			 *									'limit'=>array('min'=>$start,
			 *													'max'=>$totalNum)));
		     * 
		     *
		     * @param  array
		     * @return array com elementos da tabela da classe
		     */
				/**
		     * Consulta no banco e retona ao usuario
		     * @var tipo
		 */	
		public function ioGet($array,$params=null)
		{
			$this->select();
			if(is_array($array))
				$this->where($array);

			if(isset($params['order'])) {
				$this->order($params['order']['order'],$params['order']['column']); 
			}

			if(isset($params['limit']))
				$this->limit((isset($params['limit']['min'])) ? 
										$params['limit']['min'] : 0,
										$params['limit']['max']);

			return $this->run();
		}

		/**
		 * procura por uma entrada se encontrar substitui 
		 * senão insere nova
		 * @return [type] [description]
		 */
		public function ioUpdateOrCreate($set,$where,$params=null) {

			$result = null;
			$resultRegister = $this->ioGet($where,$params);

			/**
			 * se ja existe dá um update
			 */
			if(isset($resultRegister[0])) {
				//echo 'atualizando';
				$result = $this->ioUpdate($set,$where);
			} else {
				//echo 'criando';
				$result = $this->ioCreate($set);
			}		
			return $result;
		}	


		public function ioDelete($where)
		{		
			$this->delete();
			$this->where($where);
			return $this->run();
		}

		public function ioUpdate($set,$where)
		{
			$this->update();
			$this->set($set);
			$this->where($where);
			$this->run();
			
			$myQuery = $this->getQuery();

			/**
			 * prepara um retorno com todos os id's dos elementos modidficados
			 * @var [type]
			 */
			$resultGet = $this->ioGet($where,null);
			
			if(sizeof($resultGet) == 1)
				$result = &$resultGet[0]['id'];
			else if(sizeof($resultGet) > 1) {

				foreach($resultGet as $columnId => $element) {
					$result[$columnId] = $element['id'];
				}
			}				
			else
				$result = null;

			$this->setQuery($myQuery);
			return $result;
		}


		public function getRelation($fatherTable,$childrenTables)
		{
			//SETA O NOME DA TABELA PAI
			$fatherTable['name'] = $this->getTableName();

			if(is_array($fatherTable) && is_array($childrenTables))
			{
				$this->setTableName($fatherTable['name']);
				
				$params=null;			
				//PASSA OS PARAMETROS ADICIONAIS CASO SETADOS
				if(isset($fatherTable['addParam']))
				{
					$params = $fatherTable['addParam'];
					unset($fatherTable['addParam']);
				}
				$resultFather = $this->ioGet($fatherTable['whereClausule'],$params);		
				foreach($resultFather  as $idFather => $rowFather)
				{
					foreach($childrenTables as $childTable)
					{
						$this->setTableName($childTable['name']);
						$childResult = array();
						//pega as imagens de do item
						foreach($childTable['relationCollumn'] as $id => $relation)
						{
							$childTable['whereClausule'][$relation] = $rowFather[$fatherTable['relationCollumn'][$id]];
						}
						$params = null;	
						$childResult = $this->ioGet($childTable['whereClausule'],$params);

						$resultFather[$idFather][$childTable['name']] = $childResult;
					}	
				}
				return $resultFather;
			}
			
			return false;
		}


		//FUNÇÃO DE COMPATIBILIDADE (REMOVER NO PRÓXIMO SISTEMA)
		public function ioGetWithRelation($fatherTable,$childrenTables,$adicionalParm = null)
		{
			//SETA O NOME DA TABELA PAI
			$fatherTable['name'] = $this->getTableName();

			if(is_array($fatherTable) && is_array($childrenTables))
			{
				$this->setTableName($fatherTable['name']);
				$this->select();
				$this->where($fatherTable['whereClausule']);
				//INVERTE A ORDEM	
				if(isset($fatherTable['addParam']['order']))
				{
					$this->order();
				}
				if(isset($fatherTable['addParam']['limit']))
				{	
					$this->limit(0,$fatherTable['addParam']['limit']['max']);
				}	

				$resultFather = $this->run();
				
				foreach($resultFather  as $idFather => $rowFather)
				{
					foreach($childrenTables as $childTable)
					{
						$this->setTableName($childTable['name']);
						$childResult = array();
						//pega as imagens de do item
						foreach($childTable['relationCollumn'] as $id => $relation)
						{
							$childTable['whereClausule'][$relation] = $rowFather[$fatherTable['relationCollumn'][$id]];
						}
						
						$this->select();
						$this->where($childTable['whereClausule']);
						//echo $this->getQuery();
						$childResult = $this->run();
						$resultFather[$idFather][$childTable['name']] = $childResult;
					}	
				}
				return $resultFather;
			}
			
			return false;
		}
	}

?>