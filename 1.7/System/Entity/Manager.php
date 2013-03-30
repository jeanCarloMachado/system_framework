<?php
	/**
	 * realiza a injeção de dependẽncias das enidades
	 * @author jean
	 *
	 */
	class System_Entity_Manager
	{
		const DEFAULT_TYPE = "Input";
		const DEFAULT_TYPE_LABEL = "default";
		
		
		public static function getInstance($name=null)
		{
			$prefix = "System_Entity_";
			$entity = (!empty($name) && $name != self::DEFAULT_TYPE_LABEL) ? $name : "Input";
			
			$className = $prefix.$entity;
				
			$result = new $className;
			return $result;
		}
		/**
		 * faz inje��o de depend�ncia no objeto
		 * 
		 * @param System_Var $obj
		 */
		public static function getEntityFromObject(System_Var &$obj,$type=self::DEFAULT_TYPE_LABEL) 
		{
			$prefix = "System_Entity_";
			$entity = (!empty($name) && $name != self::DEFAULT_TYPE_LABEL) ? $name : "Input";
			
			
			if($type != self::DEFAULT_TYPE_LABEL) {
				
				$entity = $type;
				$className = $prefix.$entity;
				$result = new $className;
				
			} else if($obj->getColName() == "id") {
				
				$entity = "Input";
				$className = $prefix.$entity;
				$result = new $className;
				$result->setDisabled(true);
				
			} else if($obj->getColName() == "status" || $obj->getColName() == "visivel") {
				
				$entity = "Radio";
				$className = $prefix.$entity;
				$result = new $className;
				$result->setType("boolean");
				
			} else {
				$className = $prefix.self::DEFAULT_TYPE;
				$result = new $className;
			}
						
			
			$result->setTitle($obj->getNickName())->setName($obj->getColumnName())->setValue($obj->getVal());			
			return $result;
		}
		
		public static function getEntityArrayFromRow(System_Db_Table_AbstractRow &$row,$type=self::DEFAULT_TYPE_LABEL,$full=false) 
		{
			$result = array();
			foreach($row->vars as $column) {
				
				if(!$full && self::isBlackListed($column->getColumnName()))
					continue;
				
				$result[] = self::getEntityFromObject($column,$type);
			}
			return $result;
		}
		/**
		 * pega uma entidade complexa à partir de
		 * uma row
		 * @param string $name
		 * @param System_Db_Table_AbstractRow $row
		 */
		public static function getInstanceFromRow(System_Db_Table_AbstractRow &$row,$name="Row",$childType=self::DEFAULT_TYPE_LABEL)
		{
			$array = self::getEntityArrayFromRow($row,$childType);
			$row = self::getInstance($name);
			$row->setCells($array);
			
			return $row;
		}
		/**
		 * retorna um elemento composto por eleemtnos 
		 * menores recebendo um resultado do banco 
		 * de dados usando o toObject
		 * @param unknown $rows
		 * @param string $name
		 * @throws ErrorException
		 * @return multitype:NULL
		 */
		public static function getCompostInstanceRowArray($rows,$name="Row",$childType=null) 
		{
			$entitysArray = array();
			
			foreach($rows as $row) {
				$entitysArray[] = self::getInstanceFromRow($row,$name,$childType);
			}

			return $entitysArray;
		}
		
		/**
		 * retona uma lista defualt do pacman
		 * @param unknown $rows
		 */
		public static function getDefaultListInstance($rows,$title="Título não informado")
		{
			//pega uma linha para servir de modelo para o cabeçalho da tabela
			$exampleRow = reset($rows);
			$entitysArray = array();
			
			foreach($rows as $row) {
				
				
				$entityArr = self::getEntityArrayFromRow($row,"Text");
				$entityRow = self::getInstance("Row");
				$entityRow->setCells($entityArr);
				
				$entitysArray[] = $entityRow;
			}
			
			$instance =  System_Entity_Manager::getInstance("Table")->setTitle($title)->setRows($entitysArray);
			$entityRows = $instance->getRows();
			
			foreach($entityRows as $rowId => $entityRow) {
				$entityRow->setUrl(VIRTUAL_PATH."/pacman/".System_FrontController::getControllerName()."/editar/".$rows[$rowId]->getId()->getBruteVal());
			}
			
			return $instance;
		}
		
		
		public static function isBlackListed($colName) 
		{
			$blackList = array("status","visivel");
			
			return (in_array($colName, $blackList));
		}
	}