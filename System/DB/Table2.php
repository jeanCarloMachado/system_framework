<?php
	require_once 'System/DesignPattern/Factory/Factory.php';
	require_once 'System/DB/SQL.php';
	require_once 'System/DB/Where.php';
	require_once 'System/DB/Run/Mysql.php';
	require_once 'System/DB/Run/Zend.php';

	/* Classe abstrata que define as funções de manuseio com o 
	banco de dados os modelos que estenderem essa classe poderão
	utilizar seus métodos */
	class System_DB_Table2
	{
		//nome da tabela no banco de dados
		private $_name;

		/**
		* nome temporário da tabela
		* @var [type]
		*/
		private $_tmpName = null;
		//sql's
		private $query = "";		
		//instancia do banco de 
		private $_db; 
		/**
		* tipo de executor do banco de dados
		*/
		private $_executorPrefix = 'System_DB_Run_';

		private $_executor;

		//tipo de sql
		private $queryType = 'none';

		/**
		* objeto do tipo run
		* @var [type]
		*/
		private $_run = null;

		/**
		 * [__construct]
		 */
		public function __construct()
		{
			global $db;
		
			if(is_object($db)) {

				$this->_db = $db;
				$this->_executor= "Mysql";
			} else {

				$registry = Zend_Registry::getInstance();
				$zdb = $registry->get('db');
				if(is_object($zdb)) {
					$this->_db = $zdb;
					$this->_executor= "Zend";
				}
				else
				{
					dg("Sem banco de dados configurado!");
				}
			}
		}
		
		/**
		 * testa se foi dado um nome á tabela
		 * @return boolean [description]
		 */
		private function hasTableName()
		{
			if(isset($this->_name))
				return true;
			return false;
		}	

		/**
		 * adiciona o elemento do select
		 * @return [boolean] [description]
		 */
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
			$data = $this->atribution($data);

			if($this->hasTableName() && is_array($data))
			{	
				$setClausule = '';

				foreach($data as $collumn => $value)
				{
					$setClausule.=$collumn.'='.$value.',';
				}

				$setClausule = substr($setClausule,0,-1);

				$this->query.= 'SET '.$setClausule;

				//($this->query);
				return true;
			}
			return false;	
		}

		//PEGA O ESQUEMA DA TABELA
		public function getSchema()
		{			
			if($this->hasTableName())
			{	
				$tmp = $this->query;

				$this->queryType = 'describe';

				$this->query = 'DESCRIBE `'.$this->_name.'` ';
				//SALVA O RESULTADO
				$result = $this->run();
				//RETORNA A QUERY NORMAL
				$this->query=$tmp;

				return $result;
			}
			return false;
		}

		/**
		 * TESTA OS TIPOS E ATRIBUI ASPAS ENTRE OUTRAS
		 * OS ELEMENTOS ENTRAM AQUI NO ESQUEMA COLUNA VALOR
		 * @param  [array] $array [description]
		 * @return [array]        [description]
		 */
		public function atribution($array)
		{	
			if($this->hasTableName())
			{
				//PEGA O SCHEMA DA TABELA
				$tableSchema = $this->getSchema();
				foreach($array as $columnName => $columnValue)
				{
					$hasColumn = false;
					foreach($tableSchema as $idColumnSchema => $valuesSchema)
					{
						if($valuesSchema['Field'] == $columnName)
						{
							$hasColumn = true;

							if($valuesSchema['Type'] == 'int(11)')
								$array[$columnName] = $columnValue;
							else if($valuesSchema['Type'] == 'date')
								$array[$columnName] = "'$columnValue'";
							else 
								$array[$columnName] = "'$columnValue'";
							
							break;
						}

					}
					//SE A COLUNA PASSADA NAO EXISTE NO SCHEMA
					 if(!$hasColumn)
					 	unset($array[$columnName]);
				}
				return $array;
			}

			return false;
		}

		//VALORES DAS QUERYS DO TIPO COLUNA=>VALOR
		public function values($data=null)
		{
			$data = $this->atribution($data);

			if($this->hasTableName() && is_array($data))
			{	
				$collumnsClausule = '';
				$valuesClausule = '';

				foreach($data as $collumn => $value)
				{
					$collumnsClausule.=''.$collumn.',';	
					$valuesClausule.=''.$value.',';	
				}

				$collumnsClausule = substr($collumnsClausule,0,-1);
				$valuesClausule = substr($valuesClausule,0,-1);

				$this->query.= ' ('.$collumnsClausule.') VALUES ('.$valuesClausule.')';
				return true;
				
			}
			return false;	
		}
		
		/**
		 * CLÁUSULA WHERE
		 * @param  [type] $array=null [description]
		 * @return [type]             [description]
		 */
		public function where($array=null)
		{
			$where = new System_DB_Where;
			/**
			 * FAZ A ATRIBUIÇÃO DE ACORDO COM AS COLUNAS DAS TABELAS
			 * @var [array]
			 * @var [array]
			 */
			$conditions = $where->atribution($array,$this->getSchema());
			
			if(!empty($conditions))
			{					
				$this->query.= $where->transformInSQL($conditions);
				
				//dg($this->query);
				return true;
			}
			return true;
		}

		//ORDENA O RESULTADO DA QUERY
		public function order($order=null,$column=null)
		{
			$column = ($column) ? $column : 'id';		
			$order = ($order) ? $order : 'DESC';

			$this->query.=" ORDER BY $column $order";
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
			$query = 'SELECT LAST_INSERT_ID()';
			//return $this->getQuery();
			$result = $this->run($query);
			$result = $result[0]['LAST_INSERT_ID()'];
			return $result;
		}

		/**
		 * executa o php
		 * @param  String 	$query=null    query externa
		 * @param  DB 		$externDB=null banco de dados externo
		 * @param  String 	$type=null     tipo de banco de dados externos [Mysql,Zend]
		 * @return Array                retorno da consulta
		 */
		public function run($query=null,$externDB=null,$type=null)
		{	
			if(isset($query)) {
				$this->query = $query;
			}

			if(is_object($externDB)) {
				$this->_executor= $type;
				$this->_db = $externDB;
			} 

			$fileName = $this->_executorPrefix;
			$fileName.= isset($this->_executor) ? $this->_executor : 'Mysql';

			$obj = System_DesignPattern_Factory_Factory::Factory(array(
																			'fileName'=>$fileName,
																			'query'=>$this->query,
																  			'db' => $this->_db
														  				)
																	);
			

			$result = $obj->run();
			return $result;
		}


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

	//----------------GETTERS & SETTERS ----------------
		public function getTableName()
		{
			return $this->_name;	
		}
	
		public function getQuery()
		{
			return $this->query;
		}

		public function setQuery($query)
		{
			return $this->query = $query;
		}

		public function setTableName($name)
		{	
			$this->_name = $name;
			$this->query = '';
		}	

		public function setTmpTableName($name) {

			$this->_tmpName = $this->_name;
			$this->_name = $name;
		}

		/**
		 * altera tmpTableName para tableName e vice versa
		 * @return [type] [description]
		 */
		public function changeTableNames() {

			$tmp = $this->_tmpName;
			$this->_tmpName = $this->_name;
			$this->_name = $tmp;
		}
	//----------------FIM GETTERS & SETTERS ----------------

	}
?>