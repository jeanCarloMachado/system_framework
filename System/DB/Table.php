<?php

	/**
	 * essa classe contém as query's básicas para utilização em modelos
	 */
	abstract class System_DB_Table extends Zend_Db_Table_Abstract implements System_DB_Table_Interface
	{
		/**
		 * nome da tabela no banco de dados
		 * @var [type]
		 */
		protected $_name;

		/**
		 * obeto de retorno dos selects
		 * @var [type]
		 */
		public $_rowSet;

		/**
		 * ultimo sql executado
		 * @var [type]
		 */
		protected $_query;

		
		public function __construct() 
		{
			parent::__construct();

			$this->run("SET NAMES 'utf8'");
		}

		public function create($array)
		{	

			$array = $this->atribution($array);
			//dg($array);

  			$this->insert($array);

  			$result = $this->getLastId();
  			return $result;
		}

		/**
		 * faz a consulta ao banco de dados
		 * @param  [type] $array  [description]
		 * @param  [type] $params [description]
		 * @return [type]         [description]
		 */
		public function get($array,$params=null,$columns=null)
		{
			$this->_setQuery(array('query'=>'GET','data'=>$array));

			$where = $array;

			$order = $params['order'];
			/**
			 * total de eleemtnos à buscar
			 * @var [type]
			 */
			$count = $params['limit']['count'];
			/**
			 * à partir de qual elemento buscar
			 * @var [type]
			 */
			$offset = $params['limit']['offset'];


			$where = $this->atribution($where);

			foreach($where as $elementId => $element) {

				$where[$elementId.' = ?'] = $element;
				unset($where[$elementId]);
			}

			$result= $this->fetchAll($where,$order,$count,$offset);
			/**
			 * guarda o objeto rowset na variavel da classse
			 * @var [type]
			 */
			$this->_rowSet = $result;
			//dg(array($result));
			$result =  $result->toArray();

			return $result;
  		}


  		public function getTree($array,$params=null,$columns=null) 
  		{

  			$this->_setQuery(array('query'=>'GET TREE','data'=>$array));


  			$result = $this->get($array,$params=null,$columns=null);
  			//dg($result);

  			foreach($this->_dependentTables as $table) {

  				/**
  				 * pega somente o nome do arquivo e naõ seu nome completo
  				 * se o arquivo e  System_DB_Table retornará table
  				 * @var [type]
  				 */
  				$cleanTableName = explode('_',$table);
  				$cleanTableName = (sizeof($cleanTableName) > 1) ? strtolower($cleanTableName[sizeof($cleanTableName)-1]) : $cleanTableName[0];

  				foreach($this->_rowSet as $childId => $child) {

  					$tmp = $child->findDependentRowset($table);
  					$result[$childId][$cleanTableName] = $tmp->toArray();
  				}

  			}

  			return $result;
  		}	

  		/**
  		 * se o elemento não existe para se dar o update ele é criado
  		 * @param  [type] $set    [description]
  		 * @param  [type] $where  [description]
  		 * @param  [type] $params [description]
  		 * @return [type]         [description]
  		 */
		public function updateOrCreate($set,$where,$params=null)
		{	

			$resultGet = $this->get($where);

			if(empty($resultGet)) {
				$result = $this->create($set);
			} else {
				$result = $this->update($set,$where);
			}

			return $result;
		}

		public function delete($where)
		{

			$this->_setQuery(array('DELETE'=>'UPDATE','data'=>$where));

			$where = $this->atribution($where);

			foreach($where as $elementId => $element) {

				$where[$elementId.' = ?'] = $element;
				unset($where[$elementId]);
			}
			parent::delete($where);
		}

		public function update(array $set,$where)
		{
			/**
			 * salva-se o where limpo para posterior consulta
			 * @var [type]
			 */
			$cleanWhere = $where;

			$set = $this->atribution($set);
			$where = $this->atribution($where);

			foreach($where as $elementId => $element) {

				$where[$elementId.' = ?'] = $element;
				unset($where[$elementId]);
			}
			/**
			 * executa o update
			 */	
			$result = parent::update($set,$where);
			if(!$result)
				return false;
			/**
			 * consulta os itens modificados
			 * @var [type]
			 */
			$result = $this->get($cleanWhere,null,array('id'));

			/**
			 * SETA A QUERY
			 */
			$this->_setQuery(array('query'=>'UPDATE','data'=>$set));

			return $result;
		}

		public function getName()
		{
			return $this->_name;
		}
		
		public function setName($name)
		{
			$this->_name = $name;
		}

		/**
		 * pega o esquema da tabela
		 * @return [type] [description]
		 */
		public function getSchema()
		{		
			$sql = "DESCRIBE `".$this->_name."`";
			$result = $this->run($sql);

			return $result;
		}

		/**
		 * retorna o ultimo id inserido
		 * @return [type] [description]
		 */
		public function getLastId()
		{
			$sql = 'SELECT LAST_INSERT_ID()';
			$result = $this->_db->fetchAll($sql);
			$result = $result[0]['LAST_INSERT_ID()'];
			return $result;
		}

		/**
		 * TESTA OS TIPOS E ATRIBUI ASPAS ENTRE OUTRAS
		 * OS ELEMENTOS ENTRAM AQUI NO ESQUEMA COLUNA VALOR
		 * @param  [array] $array [description]
		 * @return [array]        [description]
		 */
		public function atribution($array)
		{	
			$tableSchema = $this->getSchema();

			foreach($array as $columnName => $columnValue)
			{
				$hasColumn = false;
				foreach($tableSchema as $idColumnSchema => $valuesSchema)
				{
					// $valuesSchema['Field'] = explode($valuesSchema['Field']);
					// $valuesSchema['Field'] = $valuesSchema['Field'][0];

					if($valuesSchema['Field'] == $columnName) 	{
						$hasColumn = true;

						break;
					}

				}
				/**
				 * caso a coluna nao exista no esquema 
				 * a remove
				 */
				 if(!$hasColumn)
				 	unset($array[$columnName]);
			}
			return $array;
		}

		/**
		 * executa um sql passado pelo usuario
		 * @param  [type] $query [description]
		 * @return [type]        [description]
		 */
		public function query($query)
		{
			$stmt = $this->_db->query($query);
			return $stmt->fetchAll();
		}


		/**
		 * 
		 * @return [type] [description]
		 */
		public function count($where = null)
		{
			$result = $this->get($where);

			return count($result);
		}

		/**
		 * executa um sql qualquer
		 * @param  [type] $sql [description]
		 * @return [type]      [description]
		 */
		public function run($sql) 
		{
			$stmt = $this->_db->query($sql);
			return $stmt->fetchAll();		
		}


		public function getQuery()
		{
		    return $this->_query;
		}
		
		public function setQuery($query)
		{
		    $this->_query = $query;
		}

		/**
		 * seta a query para a utilizacao do getquery (funcao interna)
		 * @param [type] $data [description]
		 */
		private function _setQuery($data)
		{
			//$this->_query = 'QUERY '.strtolower($data['query']).' ON TABLE '.$this->_name.' DATA: '.$data['data'].'';
			return true;
		}

		/**
		 * ---------------------------------------------------------------------------------
		 * FUNÇOES DE COMPATIBILIDADE (DEPRECATED)
		 */
		public function ioCreate($array)
		{
			return $this->create($array);
		}

		public function ioGet($array,$params=null)
		{
			return $this->get($array,$params=null,$columns=null);
		}
		
		public function ioUpdateOrCreate($set,$where,$params=null)
		{
			return $this->updateOrCreate($set,$where,$params=null);
		}

		public function ioDelete($where)
		{
			return $this->delete($where);
		}

		public function ioUpdate($set,$where)
		{
			return $this->update($set,$where);
		}

		public function getTableName()
		{
		    return $this->_name;
		}
		
		public function setTableName($tableName)
		{
		    $this->_name = $tableName;
		}
		
		
		
	}
?>
