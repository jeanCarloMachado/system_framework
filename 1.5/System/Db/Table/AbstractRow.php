<?php
	abstract class System_Db_Table_AbstractRow  implements System_DesignPattern_Factory_Interface
	{
		protected $_table = null;

		public $vars = array();

		public static function Factory($params=null)
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
				if(!empty($data[$element['Field']]))
					$this->vars[$newColumnName]->setBruteValue($data[$element['Field']]);
			}
		}
		/*
		Array
(
    [0] => Array
        (
            [Field] => id
            [Type] => int(11)
            [Null] => NO
            [Key] => PRI
            [Default] => 
            [Extra] => auto_increment
        )

    [1] => Array
        (
            [Field] => ref_interna
            [Type] => varchar(45)
            [Null] => NO
            [Key] => 
            [Default] => 
            [Extra] => 
        )

    [2] => Array
        (
            [Field] => item
            [Type] => varchar(45)
            [Null] => NO
            [Key] => 
            [Default] => 
            [Extra] => 
        )
		 */

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
			} 

			throw new Exception("método desconhecido - verfique System_DB_Table_Row");
		}

		
		public function getVar($key)
		{

		    return $this->vars[$key];
		}
		

		public function getVars()
		{
		    return $this->vars;
		}
	}
?>