<?php
	/**
	 * essa classe contÃ©m as query's bÃ¡sicas para utilizaÃ§Ã£o em modelos
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
		protected $_query = null;
		/**
		 * filtros bÃ¡sicos para serem executados 
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
		protected $alias = "sem apelido";
		/**
		 * observadores
		 * @var [type]
		 */
		protected $_observers;
		protected $onlyNotDeleted = null;
		/**
		 * flag que retorna o objeto 
		 * @var integer
		 */
		protected $_returnObj = 0;
		protected $visibleCol;
		/**
		 * colunas (default) usadas em funcionalidades do
		 * sitema
		 * @var unknown
		 */
		protected $functionColumns = array(
	
					//utilizado na funÃ§Ã£o onlyAvailable e nos controladores do ack (status visÃ­vel)
					"visible" => array (
										"name"=>"visivel",
										"enabled"=>"1",
										"disabled"=>"0"
									  ),
					//utilizado na funÃ§Ã£o onlyAvailable e onlyNotDeleted
					"status" => array (
										"name"=>"status",
										"enabled"=>"1",
										"disabled"=>"9"
										),
					"order" => "ordem asc"
		);
		/**
		 * nessa variaÃ¡vel vÃ£o os apelidos das colunas
		 * @var unknown
		 */
		protected $colsNicks = null;
		/**
		 * cache da funcÃ£o getSchema
		 * @var unknown
		 */
		protected $schemaCache = null;
		//===============FIM ATRIBUTOS===============
		/**
		 * retorna os apelidos das colunas (caso existam)
		 * @return unknown
		 */
		public function getColsNicks()
		{
			if(empty($this->colsNicks))
				return null;
		
			return $this->colsNicks;
		}
		
		public function setColsNicks($colsNicks)
		{
			$this->colsNicks = $colsNicks;
			return $this;
		}
		/**
		 * pega o apelido de uma coluna por seu 
		 * nome no banco de dados
		 * @param unknown $colName
		 */
		public function getColNick($colName) 
		{
			$result = null;
			
			if(!empty($this->colsNicks[$colName]))
				$result = $this->colsNicks[$colName];
			 
			//tenta encontrar alguma coluna quando colName foi
			//usado sem o idioma
			
			if(!$result && is_array($this->colsNicks))
			foreach($this->colsNicks as $literalName => $nick) {
				
				$newName = (System_Object_String::matchWithoutSuffixes($colName,$literalName));
				
				if($newName) {
					$result = $this->colsNicks[$newName];
					break;
				}
			}
			if(!$result)
				@$result = ucfirst(reset(explode("_",System_Language::translate($colName))));
			
			return $result;
		}
		/**
		 * retorna o nome da coluna ordem
		 * que estÃ¡ setado em $functionColumns
		 */
		public function getOrderColumnName()
		{	
			$tmp = explode(" ",$this->functionColumns["order"]);
			$result=  reset($tmp);
			return $result;
		}
		/**
		 * retorna o campo ordem
		 * setado em $funcionColumns
		 */
		public function getOrder()
		{
			//existe o campo default de ordem na tabela
			//ele ordena por este
			if($this->hasColumn($this->getOrderColumnName()))
				return $this->functionColumns["order"];
			
			return "id ASC";
		}
		
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
		
		protected function _setQuery($sql = null)
		{
			if($sql)
				$this->_query = $sql;
			else
				$this->_query = ($this->_db->getProfiler()->getLastQueryProfile()->getQuery());
				
			return $this;
		}
		
		public function getQuery()
		{
			return $this->_query;
		}
		
		public function getVisibleCol()
		{
			if(empty($this->functionColumns["visible"]))
				return array (
							  "name"=>"visivel",
							  "enabled"=>"1",
							  "disabled"=>"0"
							  );
				
				
			return $this->functionColumns["visible"];
		}
				
		public function getVisibleColName()
		{
			if(empty($this->functionColumns["visible"]))
				return "visivel";
		
			
			return $this->functionColumns["visible"]["name"];
		}

		public function __construct() 
		{
			parent::__construct();

			$this->_db->getProfiler()->setEnabled(true);
			
			$this->onlyAvailable = null;
			$this->_queryType = null;

			$this->run("SET NAMES 'utf8'");
		}
		
		public function getRowName()
		{
			return $this->_row;
		}
		
		
		public function getRowInstance()
		{
			$rowName = $this->getRowName();
			return new $rowName();
		}
		
		public function getAlias()
		{
			return $this->alias;
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
		 * quando esse mÃ©todo Ã© usado com uma
		 * query somente os status 1 e visivel 1 serao buscados
		 */
		public function onlyAvailable()
		{
			$this->onlyAvailable = true;
			return $this;
		}
		
		public function onlyNotDeleted()
		{
			$this->onlyNotDeleted = true;
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
			$avilableArr = array();
			
			if(!empty($this->functionColumns["status"]))
			{
				$avilableArr[$this->functionColumns["status"]["name"]] = $this->functionColumns["status"]["enabled"];
			}
			
			if(!empty($this->functionColumns["visible"]))
			{
				$avilableArr[$this->functionColumns["visible"]["name"]] = $this->functionColumns["visible"]["enabled"];
			}
			
			
			$where = array_merge((array) $avilableArr,(array) $where);
			$this->setOnlyAvailable(false);
			return true;
		}
		/**
		 * da merge do where com as query's
		 * dos disponiveis
		 * @param unknown $where
		 */
		private function onlyNotDeletedQuerySetup(&$where)
		{
			$notDeletedArr = array();
			
			if(!empty($this->functionColumns["status"]))
			{
				$notDeletedArr[$this->functionColumns["status"]["name"]] = $this->functionColumns["status"]["enabled"];
			}
			
			$where = array_merge((array) $notDeletedArr,(array) $where);
			$this->onlyNotDeleted = false;
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
		 * reseta a query para suas configuraÃ§Ãµes default
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
		 * adiciona filtros bÃ¡sicos da classe no
		 * where a ser executado, depois desativa o filtro
		 * @return [type] [description]
		 */
		protected function _prepareFilter(&$where)
		{	
			/**
			 * desabilita o serviÃ§o para as proximas querys
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
		
		public function factoryChilds($array)
		{
			if(!$array) {
				throw new Exception("nenhuma linha em array passada para a criaÃ§Ã£o dos modelos em objetos");
			}
			//chama o mÃ©todo estÃ¡tico Ã  partir de um objeto variÃ¡vel
			return call_user_func(array($this->_row,'Factory'),$array,$this->_row);
		}
		/**
		 * testa existe algum elemento de comparacao,
		 * caso exita, retorna a string sem ele.
		 * caso nÃ£o, retorna null
		 * @param  [type]  $str [description]
		 * @return boolean      [description]
		 */
		private function hasComparationChar ($str)
		{
			$str = explode(' ',$str);
		
			$result = (count($str) > 1) ? $str : null;
		
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
			$result = $this->_run($sql);
		
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
					 * testa se hÃ¡ algum caracter de comparaÃ§Ã£o, caso exista
					 * o remove da query
					 * @var [type]
					 */
					$columnName = ($this->hasComparationChar($columnName)) ? $columnName[0] : $columnName;
					
					//retorna se existe a coluna ou nÃ£o
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
		
		public function getTableName()
		{
			return $this->_name;
		}
		
		public function setTableName($tableName)
		{
			$this->_name = $tableName;
		}
		
		/**
		 * Retorna as chaves primarias da tabela
		 * @return Ambigous string|multitype:string
		 */
		public function getPrimaryKey(){
			$shema = $this->getSchema();
			$tmp = array();
			
			foreach($shema as $column){
				if($column['Key'] == 'PRI'){
					$tmp[] = $column['Field'];
				}
			}
			
			if(count($tmp) == 1)
				return $tmp[0];
			else
				return $tmp;
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
		public function notify($container)
		{
			if(empty($this->_observers))
				return false;
				
			foreach($this->_observers as $observerName) {
				try {
					$observer = new $observerName;
					
				} catch (Exception $e) {
					dg("observer => ".$observerName." nÃ£o existe");
					return;
				}
				$observer->listen($container);
			}
		}
		/**
		 * testa se existe uma coluna no banco com o nome passado
		 * @param unknown $columnName
		 * @return boolean
		 */
		public function hasColumn($columnName)
		{
			if(!$this->schemaCache) {
				$this->schemaCache = $this->getSchema();	
			}
			
			foreach($this->schemaCache as $col) {
				if($columnName == $col["Field"])
					return true;
			}
			
			return false;
		}
		
		//===================== FUNÃ‡Ã•ES DE SQL PARA O USUÃ�RIO =====================

		public function create(array $set)
		{	
			$set = $this->atribution($set);
  			$this->insert($set);
  			$this->_setQuery();
  			$query = $this->getQuery();

  			$result = $this->getLastId();
  			
  			if(!empty($result))
  				$result = array($result);
  			
  			/**
  			 * notifica o ocorrido
  			 */
  			$container = new System_Container_QueryInfo();
  			$container->setSet($set);
  			$container->setQuery($query);
  			$container->setResult($result);
  			$container->setModel($this);
  			$this->notify($container);
  			
  			return $result;
		}
		/**
		 * get interno (nÃ£o guarda o tipo da query)
		 * @param array $where
		 * @param string $params
		 * @param string $columns
		 */
		private function _get(array $where=null,$params=null,$columns=null)
		{
			$order = null;
			//se nÃ£o foi passado nenhuma clÃ¡usula de ordem 
			//pega a default do sistema
			if(!empty($params["order"]))
				$order = $params["order"];
			else
				$order = $this->getOrder();		
			
			/**
			 * total de eleemtnos Ã  buscar
			 * @var [type]
			 */
			$count = null;
			if(!empty($params["limit"]["count"]))	
				$count = $params['limit']['count'];
			/**
			 * Ã  partir de qual elemento buscar
			 * @var [type]
			 */
			$offset = null;
			if(!empty($params["limit"]["offset"]))	
				$offset = $params['limit']['offset'];
			
			$this->_prepareFilter($where);
			
			if($this->getOnlyAvailable())
				$this->onlyAvailableQuerySetup($where);
				
			if($this->onlyNotDeleted())
				$this->onlyNotDeletedQuerySetup($where);
			
			$where = $this->atribution($where);
			
			if(!empty($where)) {
			
				foreach($where as $elementId => $element) {
			
					$comparationChar = $this->hasComparationChar($elementId);
			
					/** se nÃ£o hÃ¡ caractere de comparacao na query,
					 coloca o default e remove a chave antiga,
					 caso o contrÃ¡trio deixa-o como veio
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
			$result= $result->toArray();
			
			
			return $result;
		}
		
		/**
		 * utilizado para cl�usulas que s� podem retornar um
		 * resultado ou nenhum (j� d� reset no resultado)
		 * @param array $where
		 * @param string $params
		 * @param string $columns
		 * @throws Exception
		 * @return NULL|mixed
		 */
		public function getOne(array $where=null,$params=null,$columns=null)
		{
			$result = $this->get($where,$params,$columns);
			
			if(empty($result))
				return null;
			
			if(count($result) > 1) {
				throw new Exception("mais de um valor retornado");
			}
			
			return reset($result);			
		}
		
		/**
		 * faz a consulta ao banco de dados
		 * @param  [type] $array  [description]
		 * @param  [type] $params [description]
		 * @return [type]         [description]
		 */
		public function get(array $where=null,$params=null,$columns=null)
		{
			$result = $this->_get($where,$params,$columns);
			
			
			//pega as linhas em forma de objetos caso as mesmas tennham sido chamadas dessa forma
			if($this->toObjectEnabled()) {
				
				if(phpversion() >= 5.4) {
					//athrow new Exception("alterar os comentÃ¡rios no get de db-table-abstract");
					$row =& $this->_row;
					$result = $row::Factory($result,$this->_row);
				} else {
					throw new Exception("alterar os comentÃ¡rios no get de db-table-abstract");
					//$result = call_user_func(array($this->_row,'Factory'),&$result,$this->_row);
				}
			}
				
			/**
			 * o mÃ©todo desativa o serviÃ§o se a funÃ§Ã£o
			 * Ã© do tipo dele
			 * @var [type]
			 */
			if($this->_queryType == 'get')
				$this->resetToQueryDefaults();
			
			/**
			 * salva o tipo da query
			 */
			$this->_setQuery();
			return $result;
  		}
  		
  		
  		/**
  		 * se o elemento nÃ£o existe para se dar o update ele Ã© criado
  		 * @param  [type] $set    [description]
  		 * @param  [type] $where  [description]
  		 * @param  [type] $params [description]
  		 * @return [type]         [description]
  		 */
		public function updateOrCreate(array $set,array $where,$params=null)
		{	
			$result = null;
			
			if(empty($where) || System_Object_Array::allElementsEmpty($where)) 
				return $this->create($set);
			
			$resultGet = $this->get($where);
			
			if(empty($resultGet)) 
				$result = array($this->create($set));
			 else
				$result = $this->update($set,$where);
			

			 if(empty($result))
			 	$result =& $resultGet;
			 
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
			$query = $this->getQuery();
			
			/**
			 * notifica o ocorrido
			 */
			$container = new System_Container_QueryInfo();
			$container->setSet($where);
			$container->setQuery($query);
			$container->setResult($result);
			$container->setModel($this);
			$this->notify($container);

			return $result;
		}
		
		/**
		 * Deleta virtualmente um registro da tabela
		 * @param array $where
		 * @return Ambigous <false, array:id`s afetados >
		 */
		public function deleteVirtual($where)
		{
			$set = array($this->functionColumns["status"]["name"] => $this->functionColumns["status"]["disabled"]);
			return $this->update($set, $where);
		}
		
		public function switchVisible($where,$status = null)
		{
			if($status == null){
				$row = $this->toObject()->getOne($where);
				$set = array($this->functionColumns["visible"]["name"] => !$row->getVisivel()->getBruteVal());
			}else{
				$set = array($this->functionColumns["visible"]["name"] => $status);
			}
			
			return $this->update($set, $where);
		}

		public function update(array $set,$where)
		{
			$cleanSet = $set;
			$cleanWhere = $where;
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
			
			//pega os valores antigos para o notify
			$prevResult = $this->_get($cleanWhere);
			/**
			 * executa o update
			 */
			$resultUpdate = parent::update($set,$where);
			$this->_setQuery();
			$query = $this->getQuery();	
			
			if(!($resultUpdate))
				return false;
			/**
			 * consulta os itens modificados
			 * @var [type]
			 */
			$resultGet = $this->_get($cleanWhere);
			
			$result = array();
			
			foreach($resultGet as $elementId => $element) {
				$result[$elementId] = $element['id'];	
			}
			
			{
				/**
				 * notifica o ocorrido
				 */
				$container = new System_Container_QueryInfo();
				$container->setPrevVals($prevResult);
				$container->setSet($cleanSet);
				$container->setQuery($query);
				$container->setResult($result);
				$container->setModel($this);
				$this->notify($container);
			}
				
			
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
		 * 
		 * @return [type] [description]
		 */
		public function count($where = null)
		{
			if(is_string($where)) {

				$result = $this->run("SELECT COUNT(*) from ".$this->getTableName()." WHERE ".$where);
				return $result[0]['COUNT(*)'];
			} else if(is_array($where)) {
				if($this->getOnlyAvailable())
					$this->onlyAvailableQuerySetup($where);
			}

			$result = $this->get($where);

			return count($result);
		}
		/**
		 * run interno (nÃ£o salva log)
		 * execuata um sql qualquer
		 * @param unknown $sql
		 */
		private function _run($sql, $bind = array())
		{
			$stmt = $this->_db->query($sql, $bind);
			$result = $stmt->fetchAll();
			$this->_setQuery($sql);
			
			/**
			 * notifica o ocorrido
			*/
			if(!$this->_isSysQuery()) {
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
		/**
		 * executa um sql qualquer
		 * @param  [type] $sql [description]
		 * @return [type]      [description]
		 */
		public function run($sql, $bind = array()) 
		{
			$result = $this->_run($sql, $bind);
  			
  			$container = new System_Container_QueryInfo();
  			$container->setQuery($sql);
  			$container->setResult($result);
  			$container->setModel($this);
  			$this->notify($container);

			return $result;	
		}
	}
?>