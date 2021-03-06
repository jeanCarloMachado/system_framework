<?php
	/**
	 * procura por categoria
	 */
	abstract class Reuse_Ack_Controller_Helper_Search_CategoryProduct_ProductTitle extends System_Search_Searchable_Abstract
	{
		/**
		 * modulo do controlador no sistema
		 */
		const MODULE = 8;
		/**
		 * modulo de categorias de produtos
		 */
		const CATEGORY_MODULE = 19;

		public function getValues(str $key) 
		{

			$category = new Reuse_Ack_Model_Category;
			$resultCategory = $category->getProductChild(null,
												array('module'=>self::MODULE,
													'categoryModule'=>self::CATEGORY_MODULE,
													'whereProduct'=>array('titulo_'.System_Language::current().' LIKE '=>'%'.$key.'%'))
												,null);
			
			$result = array();
			foreach($resultCategory as $elementId => $element) {
				$result[$element['id']] = $element;
			}	

			return $result;
		}	
	}
?>