
<?php
//namespace System;


/**
 * representa a tabela contendo a listagem de categorias de tags
 * @author jean
 *
 */
class TagCategory extends System_Db_Table_AbstractRow
{
	protected $_table = "TagCategorys";
	
	
	public function getFirstImage()
	{
		$model = new Photos();
		$result = $model->onlyAvailable()->toObject()->get(array("modulo"=>TagCategorys::moduleId,"relacao_id"=>$this->getId()->getVal()), array("limit"=>array("count"=>1)));
		
		if(empty($result))
			return new Photo();
		
		return reset($result);
	}
	/**
	 * retorna apenas os possíveis filhos de uma categoria
	 */
	public function getOnlyPotentialSlaves()
	{
		
		$result = null;
		$modelName =  $this->getTableModelName();
		$where = array();
		$whereHierarchy =  array();
		
		
		
		if($this->getId()->getBruteVal()) {
			$where = array("id !=" => $this->getId()->getBruteVal());
			$whereHierarchy =  array("slave_id"=>$this->getId()->getBruteVal());
		}
		
		//pega as categorias a qual a categoria atual ja é filha
		
		$modelHierarchy = new TagCategorysHierarchys();
		$resultHierarchy = $modelHierarchy->toObject()->get($whereHierarchy);
	
		$model = new $modelName;
		$result = $model->onlyNotDeleted()->toObject()->get($where);
		
		//melhorar esse código que está uma bosta
		foreach($result as $elementId => $element) 
		{
			foreach($resultHierarchy as $elementHierarchy) {
				if($element->getId()->getBruteVal() == $elementHierarchy->getmasterid()->getBruteVal()) {
					unset($result[$elementId]);
					break;
				}
			}
		}

		if(empty($result))
			return null;
	
		return $result;
	}

	public function getMySlavesObj()
	{
		$id = $this->getId()->getBruteVal();
		if(empty($id))
			return null;
		
		$modelHierarchys = new TagCategorysHierarchys();
		$resultHierarchys = $modelHierarchys->get(array("master_id"=>$id));

		if(empty($resultHierarchys))
			return null;
		
		
		$modelCategorys = new TagCategorys();
			
		$result = array();
			
		foreach($resultHierarchys as $elementId => $element) {
			$result = array_merge($result,$modelCategorys->onlyNotDeleted()->toObject()->get(array("id"=>$element["slave_id"])));
		}
		
		return $result;
	}
	
	public function hasSlaveById($id)
	{
		$rowId = $this->getId()->getBruteVal();
		
		if(empty($id) || empty($rowId))
			return null;
		
		$modelHierarchys = new TagCategorysHierarchys();
		$resultHierarchys = $modelHierarchys->get(array("master_id"=>$this->getId()->getBruteVal(),
														"slave_id"=>$id));
		
		if(empty($resultHierarchys))
			return null;
		
		return true;
	}
}
?>