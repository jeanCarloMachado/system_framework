<?php
	class System_Var_TypeMgr 
	{
		/**
		 * é passado argumentos a classe polimorfica
		 * a qual de acordo com esses argumentos intancia
		 * a classe necessária
		 * @param  array  $params [description]
		 * @return [type]         [description]
		 */
		public static function getInstance($params,$var)
		{
			$className =& $params;
			
			$obj = null;

			if(!$className) {
					$obj = new System_Var_Type_Default;
			} else if($className=="int" || substr($className, 0,3)=="int") {
					$obj = new System_Var_Type_Int;
			}else if(substr($className, 0,7)=="decimal") {
					$obj = new System_Var_Type_Float;
			} else if($className=="date") {
					$obj = new System_Var_Type_Date;
			} else if($className=="timestamp" || $className=="datetime") {
					$obj = new System_Var_Type_Timestamp;
					return $obj;
			}  else if($className=="money") {
					$obj = new System_Var_Type_Money;
			} else {
				$obj = new System_Var_Type_Default;
				
			}
			$obj->setAlias($className);
			$obj->setVar($var);
			return $obj;
		}
	}
?>
