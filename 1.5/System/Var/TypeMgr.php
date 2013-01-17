<?php
	class System_Var_TypeMgr implements System_DesignPattern_Polymorphism_Interface
	{
		/**
		 * é passado argumentos a classe polimorfica
		 * a qual de acordo com esses argumentos intancia
		 * a classe necessária
		 * @param  array  $params [description]
		 * @return [type]         [description]
		 */
		public static function getInstance($params)
		{

			$className =& $params;

			if(!$className) {
					$obj = new System_Var_Type_Default;
					$obj->setAlias($className);
					return $obj;
			} else if($className=="int" || substr($className, 0,3)=="int") {
					$obj = new System_Var_Type_Int;
					$obj->setAlias($className);
					return $obj;
			} else if($className=="date") {
					$obj = new System_Var_Type_Date;
					$obj->setAlias($className);
					return $obj;
			} else if($className=="money") {
					$obj = new System_Var_Type_Money;
					$obj->setAlias($className);
					return $obj;
			} else {
				$obj = new System_Var_Type_Default;
				$obj->setAlias($className);
				return $obj;
			}
			return null;
		}
	}
?>
