<?php

	/**
	 * classe de categorias
	 */
	class Reuse_ACK_Model_Category extends System_DB_Table
	{
		protected $_name = 'categorias';
		
		protected $_dependentTables = array('Reuse_ACK_Model_Image','Reuse_ACK_Model_Video','Reuse_ACK_Model_Annex');

		/**
		 * pega a árvore completa
		 * @param  array  $array   [description]
		 * @param  [type] $params  [description]
		 * @param  [type] $columns [description]
		 * @return [type]          [description]
		 */
		public function getTree(array $array,$params=null,$columns=null) 
		{
			$result = parent::getTree($array,$params,$columns);


			foreach($result as $elementId => $element) {

				foreach($element['image'] as $fotoId => $foto) {
					if($foto['status'] != 1 || $foto['visivel_'.System_Language::current()] != '1') {
						unset($result[$elementId]['image'][$fotoId]);
					}
				}

				foreach($element['annex'] as $anexoId => $anexo) {
					if($anexo['status'] != 1 || $anexo['visivel_'.System_Language::current()] != '1') {
						unset($result[$elementId]['annex'][$anexoId]);
					}
				}

				foreach($element['video'] as $anexoId => $anexo) {
					if($anexo['status'] != 1 || $anexo['visivel_'.System_Language::current()] != '1') {
						unset($result[$elementId]['video'][$anexoId]);
					}
				}
			}
			return $result;
		}

		/**
		 * faz um get e retorna os dados dos filhos (de produtos)
		 * mas sem as outras asssociaçoes dessa classe como 
		 * imagens,videos e anexos
		 * @param  array  $where   [description]
		 * @param  [type] $params  [description]
		 * @param  [type] $columns [description]
		 * @return [type]          [description]
		 */
		public function getProductChild(array $where,$params=null,$columns=null) 
		{
			$result = $this->getTree($where,$params,$columns);
			
			$product = new Reuse_ACK_Model_Product;

			/**
			 * pega todos os produtos disponíveis
			 * @var [type]
			 */
			
			$whereProduct = (isset($params['whereProduct'])) ? $params['whereProduct'] : array();

			$whereProduct['visivel'] = '1';
			$whereProduct['status']='1';

			$productResult = $product->getTree($whereProduct,$params);

			foreach($result as $categoryId => $category) {

				foreach($productResult as $productId => $productElement) {

					$categoryArray = (unserialize($productElement['categorias']));

					/**
					 * se o id da categoria existe dentro do produto
					 * adiciona o produto dentro do array da categoria
					 */
					if(array_key_exists($category['id'], $categoryArray)) {
						$result[$categoryId]['product'][] = $productElement;
					}

				}

			}

			return 	$result;
		}

	}
?>