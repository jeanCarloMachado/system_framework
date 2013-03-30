<?php
	

	/**
	 * FUNÇÕES EXTRAS DE ARRAYS
	 */
	class System_Object_Array
	{
		/**
		 * cria um novo array com os valores dos dois anteriores
		 * quando o valor das chaves forem iguais
		 * do tipo: valorArray1 => $valorArray2
		 * 
		 * @param  array $arr1 [description]
		 * @param  array $arr2 [description]
		 * @return array       [description]
		 */
		public function mergeArraysByKeys($arr1,$arr2)
		{
			$newArray = array();

			foreach($arr1 as $arr1Key => $arr1Value)
			{
				foreach($arr2 as $arr2Key => $arr2Value)
				{
					if($arr1Key ==  $arr2Key)
					{
						$newArray[$arr1Value] = $arr2Value;
						break;
					}
				}
			}
			return $newArray;
		}

		
		/**
		 * concatena arrays por uma coluna em comum retornando o novo array
		 * @param  [type] $arrays [description]
		 * @param  [type] $column [description]
		 * @return [type]         [description]
		 */
		public function mergeArraysByColumn(&$arrays,$column) 
		{
			$result = array();
			foreach($arrays as $arrayId => $array) {	
				$columnName = $array[$column];
				unset($array[$column]);	
				$result[$columnName][] = $array;			
			}

			return $result;		
		}
			
		/**
		 * dá implode de um array e de todos seus arrays filhos
		 * @param  [type] $array       [description]
		 * @param  [type] $string=null [description]
		 * @return [type]              [description]
		 */
		public function implodeRecursively($array,$string=null) 
		{

			if(is_array($array)) {
				foreach($array as $columnName => $element) {
					if(is_array($element)) {
						$string.= $columnName."=>".$this->implodeRecursively($element,$string);
					} else  {
						$string.= $columnName."=>".$element."|";
					}
				}
			}

			return $string;
		}
		
		
		public static function allElementsEmpty(array &$array)
		{
			if(!is_array($array))
				return true;
			
			foreach($array as $element) {
				if(!empty($element))
					return false;
			}
			
			
			return true;
		}

		/**
		 * chama uma função recursivamente
		 * @param  [type] $arr        [description]
		 * @param  [type] $funcName   [description]
		 * @param  [type] $funcParams [description]
		 * @return [type]             [description]
		 */
		public function funcRecursively($arr,$funcName,$funcParams=null) 
		{
			$result = array();

				if(is_array($arr)) {
				foreach($arr as $elementId => $element) {
					
					if(is_array($element)) {
						
						$tmpResult = $funcRecursively($element,$funcName,$funcParams);

						foreach($tmpResult as $tmpElementId => $tmpElement) {
							$result[] = $tmpElement;
						}

					} else  {
						$result[] = $funcName($element,$funcParams);
					}
				}
			}

			return $result;
		}

		/**
		 * faz ordenação buble de um array pela coluna passada
		 * @param  [type] &$array [description]
		 * @param  [type] $key    [description]
		 * @return [type]         [description]
		 */
		public function sortByInternalKey(&$array,$key,$order = 'asc')
		{	

			$order = strtolower($order);
			$size = sizeof($array);


			if($order == 'asc') {

				for ($i=0; $i<$size; $i++) {

			       	 for ($j=0; $j<$size-1-$i; $j++) {

			            if ($array[$j+1][$key] < $array[$j][$key]) {

		            		$this->swap($array, $j, $j+1);
						}
					}
				}

			} else {

					for ($i=0; $i<$size; $i++) {

			       	 for ($j=0; $j<$size-1-$i; $j++) {

			            if ($array[$j+1][$key] > $array[$j][$key]) {

		            		$this->swap($array, $j, $j+1);
						}
					}
				}		
			}

			return $array;
		}


		private function swap(&$arr, $a, $b) 
		{
		    $tmp = $arr[$a];
		    $arr[$a] = $arr[$b];
		    $arr[$b] = $tmp;
		}


		/**
		 * converte um objeto para array
		 * @param  [type] $object [description]
		 * @return [type]         [description]
		 */
		public function objectToArray($object) 
		{
			if (is_object($object)) 
			{
				$object = get_object_vars($object);
			}

			if (is_array($object)) {
				return array_map(__FUNCTION__, $object);
			} else {
				return $object;
			}
		}

	}