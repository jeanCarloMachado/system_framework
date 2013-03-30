
<?php
//namespace System;

class Tag extends System_Db_Table_AbstractRow
{
	protected $_table = "Tags";
	
	/**
	 * retorna somente as categtorias em potencial para a tag
	 */
	public function getOnlyPotentialCategorysObj()
	{
		$modelCategory = new TagCategorys();
		$result = $modelCategory->onlyNotDeleted()->toObject()->get();
		return $result;
	}
	
	/**
	 * testa se o elemento tem o id da categoria passado
	 * @param unknown $id
	 * @return Ambigous <[type], mixed>|boolean
	 */
	public function hasCategoryById($id)
	{
		$rowId = $this->getId()->getBruteVal();
		if(empty($id) || empty($rowId))
			return false;
		
		$modelCategory = new TagsCategorys();
		$result = $modelCategory->get(array("tag_id"=>$this->getId()->getBruteVal(),"category_id"=>$id));
		
		$result = empty($result) ? false : true;
		
		return $result;
	}
}
?>