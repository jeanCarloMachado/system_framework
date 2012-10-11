<?php
	/**
	 * padrão para efetuar pesquisas no sistema
	 */
	abstract class System_Search_Abstract implements System_Search_Interface
	{
		/**
		 *
		 * @param  str    $key [description]
		 * @return [type]      [description]
		 */
		function search(str $key) 
		{
			$objNames = $this->classSelector($key);

			$result = array();

			foreach($objNames as $objId => $objName) {
				
				$obj = new $objName;

				foreach($obj->getValues($key) as $elementId => $element) {

					$this->mergeResult($result,$element,$elementId);				
				}
			}
			
			return $result;
		}

		/**
		 * retorna o nome das clases de devem efetuar a pesquisa
		 * @param  [type] $key [description]
		 * @return [type]      [description]
		 */
		abstract function classSelector($key);

		/**
		 * funçao que recombina cada elemento retornado na pesquisa no array de saída
		 * @param  array  $arr       [description]
		 * @param  [type] $element   [description]
		 * @param  [type] $elementId [description]
		 * @return [type]            [description]
		 */
		public function mergeResult(array &$arr,$element,$elementId) 
		{
				
			$arr[$elementId] = $element;	

			return $arr;
		}	
	}
?>