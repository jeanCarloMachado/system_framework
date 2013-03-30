<?php

class Category extends Reuse_Ack_Model_Category
{
	protected $_table = "Categorys";
	
	public function getFaqs()
	{
		$modelFaqs = new Faqs;
		return $modelFaqs->toObject()->onlyAvailable()->get(array("id_category"=>$this->getId()->getVal()));
	}
	
	
}
?>