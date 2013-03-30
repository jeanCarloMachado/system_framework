<?php
	
	/**
	 * procura categorias e produtos (retorna-os em conjunto produtos aninhados em suas categorias)
	 */
	abstract class Reuse_Ack_Controller_Helper_Search_CategoryProduct extends System_Search_Abstract 
	{

		/**
		 * retorna o nome das clases de devem efetuar a pesquisa
		 * @param  [type] $key [description]
		 * @return [type]      [description]
		 */
		public function classSelector($key)
		{
			$result = array();
			
			if(!$key) {
				$result = array("Reuse_Ack_Controller_helpers_Search_CategoryProduct_CategoryTitle");
			} else {
				$result = array("Reuse_Ack_Controller_helpers_Search_CategoryProduct_ProductTitle","Reuse_Ack_Controller_helpers_Search_CategoryProduct_CategoryTitle",);
			}
			return $result;
		}

		/**
		 * funçao que recombina cada elemento retornado na pesquisa no array de saída
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