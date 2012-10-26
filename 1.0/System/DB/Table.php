<?php
	/**
	 * essa classe contém as query's básicas para utilização em modelos
	 */
	abstract class System_DB_Table extends Zend_Db_Table_Abstract implements System_DB_Table_Interface,System_DesignPattern_Observer_Interface 
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

		public function create(array $set)
		{	

			$set = $this->atribution($set);


  			$this->insert($set);

  			$result = $this->getLastId();
  			return $result;
		}

		/**
		 * faz a consulta ao banco de dados
		 * @param  [type] $array  [description]
		 * @param  [type] $params [description]
		 * @return [type]         [description]
		 */
		public function get(array $where,$params=null,$columns=null)
		{
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

				$comparationChar = $this->hasComparationChar($elementId);

				/** se não há caractere de comparacao na query,
					coloca o default e remove a chave antiga,
					caso o contrátrio deixa-o como veio
				 **/
				if(!$comparationChar) {
					$where[$elementId.' = ?'] = $element;
				} else {
					$where[$elementId.' ?'] = $element;
				}
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

  		/**
  		 * testa existe algum elemento de comparacao,
  		 * caso exita, retorna a string sem ele.
  		 * caso não, retorna null
  		 * @param  [type]  $str [description]
  		 * @return boolean      [description]
  		 */
  		private function hasComparationChar($str) 
  		{
  			$str = explode(' ',$str);

  			$result = (count($str) > 1) ? $str : null;

  			return $result;
  		}

  		public function getTree(array $array,$params=null,$columns=null) 
  		{
  			
  			$result = $this->get($array,$params,$columns);

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
		public function updateOrCreate(array $set,array $where,$params=null)
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
					/**
					 * testa se há algum caracter de comparação, caso exista 
					 * o remove da query
					 * @var [type]
					 */
					$columnName = ($this->hasComparationChar($columnName)) ? $columnName[0] : $columnName;

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
		public function query(string $sql)
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

	
		public function getTableName()
		{
		    return $this->_name;
		}
		
		public function setTableName($tableName)
		{
		    $this->_name = $tableName;
		}
		
		
		/**
		 * adiciona um objeto aos observadores
		 * @param  [type] $obj [description]
		 * @return [type]      [description]
		 */
		public function attach($objName)
		{
			$this->_observers[] = $objName;
		}

		/**
		 * remove um objeto pela chave
		 * @param  [type] $obj [description]
		 * @return [type]      [description]
		 */
		public function detach($objName)
		{
			foreach($this->_observers as $observerId => $observer) {
				if($objName == $observer) {
					unset($this->_observers[$observerId]);
					return true;
				}
			}
			return false;
		}

		/**
		 * notifica os observadores
		 * @param  [type] $message [description]
		 * @return [type]          [description]
		 */
		public function notify($message)
		{
			foreach($this->_observers as $observerName) {
				$observer = new $observerName;
				$observer->listen($message);
			}
		}

		public function __destruct(){}
		
	}
?>
