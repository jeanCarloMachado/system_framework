<?php

	require_once 'DBTable.php';

	class IOBase extends DBTable
	{

		public function __construct($_name)
		{
			$this->setTableName($_name);
		}

		//insere uma linha na tabela especificada		
		public function ioCreate($array)
		{
			$this->insert();
			$this->values($array);
			$result = $this->run();

			return $result;
		}

		public function ioGet($array,$params=null)
		{
			$this->select();
			if(is_array($array))
				$this->where($array);

			if(isset($params['order']))
				$this->order($params['order']['order'],$params['order']['column']);

			if(isset($params['limit']))
				$this->limit((isset($params['limit']['min'])) ? 
										$params['limit']['min'] : 0,
										$params['limit']['max']);

			return $this->run();
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
			$result = $this->run();

			return $result;
		}

		/*
		//funçao para retornar objetos relacionados 
		//śo funciona com um pai e um filho (arrumar)
		//exemplo de uso
		$tabelaPai['name']  = 'categorias';			
		$tabelaPai['relationCollumn'][0]  = 'id';
		$tabelaPai['relationCollumn'][1]  = 'modulo';
		$tabelaPai['whereClausule'] = array('status' => '1',
											'visivel'=> '1',
											'relacao_id' => '3');

		$tabelasFilhas[0]['name']  = 'fotos';
		$tabelasFilhas[0]['relationCollumn'][0] = 'relacao_id';
		$tabelasFilhas[0]['relationCollumn'][1]  = 'modulo';
		$tabelasFilhas[0]['whereClausule'] = array('status' => '1',
												   'visivel_pt' => '1');
		
		$result = $this->ioGetWithRelation($tabelaPai,$tabelasFilhas);
		*/
		public function ioGetWithRelation($fatherTable,$childrenTables)
		{
			if(is_array($fatherTable) && is_array($childrenTables))
			{
				$this->setTableName($fatherTable['name']);
				$this->select();
				$this->where($fatherTable['whereClausule']);
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