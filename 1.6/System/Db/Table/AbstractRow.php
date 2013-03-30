<?php
	abstract class System_Db_Table_AbstractRow
	{
		protected $_table = null;

		
		public $vars = array();
		
		
		public function getTableModelName()
		{
			return $this->_table;
		}
		/**
		 * constroi um objeto do tipo linha
		 * @param string $params
		 * @return Ambigous <NULL, multitype:unknown >
		 */
		public static function Factory(&$params=null)
		{
			$extraArgs = func_get_args();
			
			/**
			 * pega o nome da classe
			 * pois não há linkagem atrada em php's < 3.5
			 * @var [type]
			 */
			$rowClass = ($extraArgs[1]);

			$result = null;

			if(is_array($params)) {

				$result = array();

				foreach($params as $elementId => $element) {
					$result[] = new $rowClass($element);
				}
			}

			return $result;
		}

		private static function treatField($fieldName)
		{
			$fieldName = explode('_',$fieldName);
			if(count($fieldName)> 1)
			{	
				$result = "";
				foreach($fieldName as $element) {
					$result.= $element;
				}
				return $result;
			} 
			return reset($fieldName);
		}

		public function __construct($data)
		{
			$model =  new $this->_table;

			if(!$model instanceof System_Db_Table_Abstract)
				throw new Exception('atributo $_table deve estar setado, e ser instancia de System_Db_Table_Abstract');

			$schema = ($model->getSchema());	

			foreach($schema as $elementId => $element) {

				$newColumnName = self::treatField($element['Field']);

				$this->vars[$newColumnName] = new System_Var;
				$this->vars[$newColumnName]->setType($element['Type']);
				$this->vars[$newColumnName]->setColumnName($element['Field']);
				$this->vars[$newColumnName]->setTable($this->getTableModelName());
				if(!empty($data[$element['Field']]))
					$this->vars[$newColumnName]->setBruteValue($data[$element['Field']]);
			}
		}

		public function __call($method, array $args)
		{
			/**
			 * [$methodName description]
			 * @var [type]
			 */
			$attrName = strtolower(substr($method, 3));
				
			$action = substr($method,0,3);

			if($action == "get") {
				return $this->getVar($attrName);
			} else if($action == "set") {

				$val = reset($args);
				
				return $this->setVar($attrName,$val);
			}

			throw new Exception("método desconhecido (".$attrName.") - verfique System_DB_Table_Row");
		}


		public function setVar($column,$value)
		{
			if(!$this->vars[$column]) { 
				if ($this->vars[$key.System_Language::current()]) {
					$column  = $key.System_Language::current();
				} else {
					throw new Exception("Não foi possível encontrar a coluna: ( $column) ");
				}
			}
			$this->vars[$column]->setBruteValue($value);		
				
			return $this;	
		}	

		/**
		 * salva o estado atual do objeto no banco de dados
		 * @return System_Db_Table_AbstractRow
		 */
		public function save()
		{
			if(!($this->getId()->getBruteVal()))	
				throw new Exception("Objeto vazio não foi possível salvar");

			$model = $this->getTableInstance();
			$oldObject = $model->toObject()->getOne(array("id"=>$this->getId()->getBruteVal()));
			
			$set = array();
			foreach($this->getVars() as $key=> $column) {
				
				$oldColumn = $oldObject->vars[$key];
				if($oldColumn->getBruteVal() != $column->getBruteVal())
					$set[$column->getColName()] = $column->getBruteVal();		
			}
			
 			$result = $modelTable = $this->getTableInstance()->update($set,array("id"=>$this->getId()->getBruteVal()));
 			
			return $this;
		}	
		
		public function getVar($key)
		{
			if($this->vars[$key]) {
				return $this->vars[$key];
			} elseif($this->vars[$key.System_Language::current()]) {
				return $this->vars[$key.System_Language::current()];
			} else {				
				$var =  new System_Var;
				$var->setValue("Coluna não existente ( $key )");
				return $var;
			}
		}

		public function getCols()
		{
			return $this->vars;
		}
		
		public function getVars()
		{
		    return $this->vars;
		}
		
		public function getTableInstance()
		{
			$modelName = $this->getTableModelName();
			$result = new $modelName();
			
			return $result;			
		}
		
		/**
		 * testa se uma linha tem conteúdo 
		 * em algum idioma (se ela tem o prefixo do idioma)
		 * e se essa coluna está preenchida com algum dado
		 * @param unknown $lang
		 */
		public function hasLangContent($lang = "pt")
		{
			foreach($this->getCols() as $column) {
								
				if($lang == System_Object_String::hasLangSuffix($column->getColName())) {
					
					
					$result = $column->getVal();
					if(!empty($result))
						return true;
				}
			}
			
			return false;
		}
		
		/**
		 * retorna um objeto de outro modelo referenciado por uma coluna 
		 * dessa linha
		 * @param unknown $modelName esse é o nome do modelo que a dependencia representa
		 * @param unknown $colName nome da coluna dessa linha que referencia 
		 * @param string $modelColName nome da coluna da tabela externa
		 * @return unknown|NULL|mixed
		 */
		public function getOuterRef($modelName,$colName,$modelColName = "id")
		{
			$model = new $modelName;
			$rowModelName = $model->getRowName();
			
			$colToMethod = str_replace("_", "", $colName);
			$getMethodName = "get".$colToMethod;
			
			$id = $this->$getMethodName()->getBruteVal();
			if(empty($id))
				return new $rowModelName;
			
			$where = array($modelColName=>$id);
			$result = $model->onlyNotDeleted()->toObject()->get($where);
			
			if(empty($result)){
				return new $rowModelName;
			}	
			
			$result = reset($result);
			return $result;
		}
	}
?>