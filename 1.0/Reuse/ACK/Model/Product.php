<?php 
	/**
	 * classe de categorias
	 */
	class Reuse_ACK_Model_Product extends System_DB_Table
	{
		protected $_name = 'produtos';

		/**
		 * seta as tabelas dependentes de um produto
		 * @var array
		 */
		protected $_dependentTables = array('Reuse_ACK_Model_Image','Reuse_ACK_Model_Video','Reuse_ACK_Model_Annex');

	

		public function getTree(array $array,$params=null,$columns=null) 
		{
			$result = parent::getTree($array,$params,$columns);

			$arr['result'] = &$result;
			$arr['params'] = $params;	

			$result = System_Helper_ChildSelector::run($arr);

			return $result;
		}

		/**
		 * retorna todos os filhos e as categorias a que esse produto pertence
		 * @param  array  $array   [description]
		 * @param  [type] $params  [description]
		 * @param  [type] $columns [description]
		 * @return [type]          [description]
		 */
		public function getChildAndParents(array $array,$params=null,$columns=null) 
		{
			/** @var pega os filhos */
			$result = parent::getTree($array,$params,$columns);

			$arr['result'] = &$result;
			$arr['params'] = $params;	

			$result = System_Helper_ChildSelector::run($arr);


			$category = new Reuse_ACK_Model_Category;

			foreach($result as $elementId => $element) {
				$categoryArray = (unserialize($element['categorias']));

				foreach($categoryArray as $categoryElementId => $categoryElement) {
					$tmp = $category->get(array('id'=>$categoryElementId));
					$result[$elementId]['categorys'][] = $tmp[0];
				}
			}	

			return $result;
		}
	}
?>