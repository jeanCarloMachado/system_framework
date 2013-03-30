
<?php
//namespace System;

class FaqCategory extends System_Db_Table_AbstractRow
{
	protected $_table = "FaqCategorys";
	
	public function getFaqs()
	{
		$model = new Faqs;
		$result = $model->onlyAvailable()->toObject()->get(array("category_id"=>$this->getId()->getBruteVal()));
		
		if(empty($result))
			return array(new Faq());
		
		return $result;
	}
}
?>