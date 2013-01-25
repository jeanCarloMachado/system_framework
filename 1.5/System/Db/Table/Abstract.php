<?php
	/**
	 * essa classe contém as query's básicas para utilização em modelos
	 */
	abstract class System_Db_Table_Abstract extends Zend_Db_Table_Abstract implements System_DesignPattern_Observer_Interface 
	{
		
		const moduleName = null;
		
		/**
		 * nome da tabela no banco de dados
		 * @var [type]
		 */
		protected $_name;

		/**
		 * obeto de retorno dos selects
		 * @var [type]
		 */
		protected $_row;


		protected $_rowObj;

		/**
		 * ultimo sql executado
		 * @var [type]
		 */
		protected $_query;
		/**
		 * filtros básicos para serem executados 
		 * sempre que o servico estiver habilitado
		 * @var [type]
		 */
		protected $_filter = array();


		protected $_filterStatus = 0;

		/**
		 * flag de query do sistema
		 * @var integer
		 */
		private $_sysQuery = 0;


		/**
		 * observadores
		 * @var [type]
		 */
		protected $_observers;

		/**
		 * flag que retorna o objeto 
		 * @var integer
		 */
		protected $_returnObj = 0;
		
		
		
		protected $onlyAvailableClausules = array(
				"visivel"=>9,
				"status" => 1
		);

		
		public function __construct() 
		{
			parent::__construct();

			$this->_db->getProfiler()->setEnabled(true);

			$this->run("SET NAMES 'utf8'");
		}

		/**
		 * retorna um objeto em vez de retornar array
		 * @return [type] [description]
		 */
		public function toObject()
		{
			$this->_returnObj = 1;
			return $this;
		}
		
		/**
		 * quando esse método é usado com uma
		 * query somente os status 1 e visivel 1 serao buscados
		 */
		public function onlyAvailable()
		{
			$this->onlyAvailable = true;
			return $this;
		}
		
		public function setOnlyAvailable($status=false)
		{
			$this->onlyAvailable = $status;
			return $this;
		}
		
		public function getOnlyAvailable()
		{
			return $this->onlyAvailable;
		}
		
		/**
		 * da merge do where com as query's
		 * dos disponiveis
		 * @param unknown $where
		 */
		private function onlyAvailableQuerySetup(&$where)
		{
		
			$where = array_merge((array) $this->onlyAvailableClausules,(array) $where);
			
			$this->setOnlyAvailable(false);
			
			return true;
		}
		
		public function toObjectEnabled()
		{
			return $this->_returnObj;
		}

		private function setToObjectEnabled($status)
		{
			$this->_returnObj = $status;
			return $this;
		}

		/**
		 * reseta a query para suas configurações default
		 * @return [type] [description]
		 */
		public function resetToQueryDefaults()
		{
			$this->_returnObj = 0;
			$this->queryType = null;
		}

		private function _queryType()
		{
			return $this->_queryType;
		}

		/**
		 * ativa o servico de filtros 
		 * basicos e retorna o objeto configurado
		 * @param  [type] $status [description]
		 * @return [type]         [description]
		 */
		public function useFilter()
		{
			$this->_filterStatus = 1;
			return $this;
		}

		public function disableFilter()
		{
			$this->_filterStatus = 0;
			return $this;
		}

		/**
		 * adiciona filtros básicos da classe no
		 * where a ser executado, depois desativa o filtro
		 * @return [type] [description]
		 */
		protected function _prepareFilter(&$where)
		{	
			/**
			 * desabilita o serviço para as proximas querys
			 */
			if(!$this->_filterStatus)
				return false;

			$this->disableFilter();

			if(!is_array($where))
				$where = array();

			foreach($this->_filter as $filterId => $filter)
			{	
				$where[$filterId] = $filter;
			}

			return true;
		}

		public function create(array $set)
		{	

			$set = $this->atribution($set);
  			$this->insert($set);

  			$this->_setQuery();

  			$result = $this->getLastId();
			/**
  			 * notifica o ocorrido
  			 */
  			$this->notify(array('action'=>"create",
								'affected'=>$result,
								'value'=>$set));

  			return $result;
		}

		/**
		 * faz a consulta ao banco de dados
		 * @param  [type] $array  [description]
		 * @param  [type] $params [description]
		 * @return [type]         [description]
		 */
		public function get(array $where=null,$params=null,$columns=null)
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


			$this->_prepareFilter($where);

			if($this->getOnlyAvailable())
				$this->onlyAvailableQuerySetup($where);

			$where = $this->atribution($where);

			if(!empty($where)) {

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

			}
			$result= $this->fetchAll($where,$order,$count,$offset);
			$this->_setQuery();

			$result= $result->toArray();

			if($this->toObjectEnabled()) {
				$result = call_user_func(array($this->_row,'Factory'),$result,$this->_row);
			}

			/**
			 * o método desativa o serviço se a função
			 * é do tipo dele
			 * @var [type]
			 */
			if($this->_queryType == 'get')
				$this->resetToQueryDefaults();


			return $result;
  		}

  		/**
  		 * testa existe algum elemento de comparacao,
  		 * caso exita, retorna a string sem ele.
  		 * caso não, retorna null
  		 * @param  [type]  $str [description]
  		 * @return boolean      [description]
  		 */
  		private function hasComparationChar ($str) 
  		{
  			$str = explode(' ',$str);

  			$result = (count($str) > 1) ? $str : null;

  			return $result;
  		}

  		public function getTree(array $array=null,$params=null,$columns=null) 
  		{
  			$result = $this->get($array,$params,$columns);

  			$this->_setQuery();
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

					if($this->toObjectEnabled()) {
						throw new Exeption("retornar objeto pra getTree ainda não foi implementado");
						$result[$childId][$cleanTableName] = $tmp->toArray();
					} else {
						$result[$childId][$cleanTableName] = $tmp->toArray();
					}
			
  				}

  			}

				/**
			 * o método desativa o serviço se a função
			 * é do tipo dele
			 * @var [type]
			 */
			if($this->_queryType == 'getTree')
				$this->resetToQueryDefaults();


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
				$result = array($this->create($set));
			} else {
				$result = $this->update($set,$where);
			}

			return $result;
		}

		public function delete($where)
		{

			$this->_prepareFilter($where);

			$where = $this->atribution($where);

			foreach($where as $elementId => $element) {

				$where[$elementId.' = ?'] = $element;
				unset($where[$elementId]);
			}
			$result = parent::delete($where);

			$this->_setQuery();


			/**
  			 * notifica o ocorrido
  			 */
  			$this->notify(array('action'=>"delete",'affected'=>$result,'where'=>$where));
		}

		public function update(array $set,$where)
		{

			$this->_prepareFilter($where);
			/**
			 * salva-se o where limpo para posterior consulta
			 * @var [type]
			 */
			$cleanWhere = $where;
			$this->atribution($set);
			$this->atribution($where);

			foreach($where as $elementId => $element) {

				$where[$elementId.' = ?'] = $element;
				unset($where[$elementId]);
			}

			/**
			 * executa o update
			 */	
			$resultUpdate = parent::update($set,$where);

			$this->_setQuery();

			if(!$resultUpdate)
				return false;
			/**
			 * consulta os itens modificados
			 * @var [type]
			 */
			$resultGet = $this->get($cleanWhere,null,array('id'));

			$result = array();
			foreach($resultGet as $elementId => $element) {
				$result[$elementId] = $element['id'];
			}
			/**
  			 * notifica o ocorrido
  			 */
  			$this->notify(array('action'=>"update",
								'affected'=>$resultUpdate,
								'value'=>$set,
								'where'=>$where));

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
			$this->_enableSysQuery();
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
		public function atribution(&$array)
		{		
			$tableSchema = $this->getSchema();

			if(empty($array)) 
				return null;

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

					if($valuesSchema['Field'] == $columnName || "LOWER(".$valuesSchema['Field'].")" == $columnName) 	{
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
			if(is_string($where)) {

				$result = $this->run("SELECT COUNT(*) from ".$this->getTableName()." WHERE ".$where);
				return $result[0]['COUNT(*)'];

			}

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
			$result = $stmt->fetchAll();	

			$this->_setQuery();

			/**
  			 * notifica o ocorrido
  			 */
  			if(!$this->_isSysQuery()) {
  				$this->notify(array("action"=>'sql',
  									"affected"=>$result,
  									"query"=>$sql));

  				if($this->toObjectEnabled()) {
					$result = call_user_func(array($this->_row,'Factory'),$result,$this->_row);

					/** desativa o to object */
					$this->setToObjectEnabled(!$this->toObjectEnabled());
				}

  				
  			} else {

  				$this->_disableSysQuery = 0;
  			}

			return $result;	
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
			if(empty($this->_observers))
				return false;


			$message['table'] = $this->getTableName();

			foreach($this->_observers as $observerName) {

				if(file_exists(System_Autoloader_Loader_Library::getPath($observerName))) {
				$observer = new $observerName;
				$observer->listen($message);
				}
			}
		}

		public function __destruct(){}

		private function _enableSysQuery()
		{
			$this->_sysQuery = 1;
		}


		private function _disableSysQuery()
		{
			$this->_sysQuery = 0;
		}

		private function _isSysQuery()
		{
			return $this->_sysQuery;
		}

		protected function _setQuery()
		{
			$this->_query = ($this->_db->getProfiler()->getLastQueryProfile()->getQuery());
			//Zend_Debug::dump($this->_db->getProfiler()->getLastQueryProfile()->getQueryParams());
			return $this;
		}

		public function getQuery()
		{
			return $this->_query;
		}
	}
?>
