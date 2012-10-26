<?php
	/* Classe abstrata que define as funções de manuseio com o 
	banco de dados os modelos que estenderem essa classe poderão
	utilizar seus métodos */
	abstract class DBTable
	{
		//nome da tabela no banco de dados
		private $_name;
		//sql's
		private $query = '';		
		//instancia do banco de 
		private $_db; 
		//tipo de sql
		private $queryType = 'none';


		public function __construct()
		{
			/*if(isset($database))
				$this->_db = $database;*/
			//passa a instancia global para a classe
            
		            global $db;
		            $this->_db = $db;
		            $this->query = '';
		}

				//testa se foi dado um nome á tabela
		private function hasTableName()
		{
			if(isset($this->_name))
				return true;
			return false;
		}		

		public function getQuery()
		{
			return $this->query;
		}

		protected function setTableName($name)
		{	
			$this->_name = $name;
			$this->query = '';
		}

		public function select()
		{
			if($this->hasTableName())
			{
				 $this->query = 'SELECT * FROM '.$this->_name; 
				 return true;
			}
			return false;
		}

		public function insert()
		{
			if($this->hasTableName())
			{
				$this->queryType = 'insert';

				$this->query = 'INSERT INTO `'.$this->_name.'`';
				return true;
			}
			return false;
		}

		public function update()
		{
		
			if($this->hasTableName())
			{
				$this->queryType = 'update';

				$this->query = 'UPDATE `'.$this->_name.'` ';
				return true;
			}
			return false;
		}


		public function delete()
		{
			if($this->hasTableName())
			{
				$this->queryType = 'delete';

				$this->query = 'DELETE  FROM `'.$this->_name.'` ';
				return true;
			}
			return false;
		}

		//set for update
		public function set($data)
		{
			if($this->hasTableName() && is_array($data))
			{	
				if($this->queryType == 'update')
				{
					$setClausule = '';

					foreach($data as $collumn => $value)
					{
						$value = (is_numeric($value))? $value : '"'.$value.'"';
						$setClausule.=''.$collumn.'='.$value.',';
					}

					$setClausule = substr($setClausule,0,-1);

					$this->query.= 'SET '.$setClausule;
					return true;
				}
			}
			return false;	
		}

		public function values($data=null)
		{
			if($this->hasTableName() && is_array($data))
			{	
				$collumnsClausule = '';
				$valuesClausule = '';

				foreach($data as $collumn => $value)
				{
					$collumnsClausule.=''.$collumn.',';
					$valuesClausule.='"'.$value.'",';	
				}

				$collumnsClausule = substr($collumnsClausule,0,-1);
				$valuesClausule = substr($valuesClausule,0,-1);

				$this->query.= ' ('.$collumnsClausule.') VALUES ('.$valuesClausule.')';
				return true;
				
			}
			return false;	
		}
		
		public function where($conditions=null)
		{
			if(is_array($conditions))
			{
				$total = count($conditions);

				$whereClausule = ' WHERE ';
				$counter = 1;
				foreach($conditions as $id => $condition)
				{
					if($counter == $total )
					{
						$whereClausule.=' '.$id.'="'.$condition.'"';
					}
					else
					{
						$whereClausule.=' '.$id.'="'.$condition.'" AND ';
					}
					$counter++;
				}
				$this->query.=$whereClausule;
				return true;
			}

			return true;
		}
		//adiciona o limite de elementos à query
		public function limit($startRow="n",$numberOfRows="n")
		{
			if(!$startRow)
				$startRow = 0;

			if(is_numeric((int) $startRow) && is_numeric((int) $numberOfRows))
			{
				$this->query.=' LIMIT '.$startRow.','.$numberOfRows.' ';
				return true;
			}
			return false;
		}

		public function getLastId()
		{
			$this->query = 'SELECT LAST_INSERT_ID()';
			//return $this->getQuery();
			$result = $this->run();
			$result = $result[0]['LAST_INSERT_ID()'];
			return $result;
		}
		//executa o php
		public function run($query=null,$externDB=null)
		{
			global $db;
			$this->_db = $db;

            if(isset($externDB))
            	$this->_db = $externDB;
			if(isset($query))
				$this->query = $query;
			try
			{
				$mysql = $this->_db->prepare($this->query);
				$mysql->execute();
				return $mysql->fetchAll();
			} 
			catch (Exception $e)
			{
				return false;
			}
		}

		//ORDENA O RESULTADO DA QUERY
		public function order($order=null,$column=null)
		{
			$column = ($column) ? $column : 'id';		
			$order = ($order) ? $order : 'DESC';

			$this->query.=" ORDER BY $column $order";
		}

		/*
			IMPLEMENTAR NA CLASSE	

		 public function timeDiff($time1,$time2)
		{
			$query = "select TIMEDIFF ('$time1' , '$time2') ";
			$result = $this->run($query);
			return $result;
		}*/


	/* FUNÇÕES PRONTAS (colocar em outro lugar) */
		public function getById($id,$idName = 'id')
		{
			$where = array($idName => $id);
			$this->select();
			$this->where($where);
			$result = $this->run();
			return $result[0];
		}

		//função que retorna o primeiro elemento
		//pode ser chamada diretamente		
		public function getFirst()
		{

			$this->select();
			$result = $this->run();
			return $result[0];
		}
	/* FUNÇÕES PRONTAS (colocar em outro lugar) */
	}
?>