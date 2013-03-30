<?php
//namespace System;

class Reuse_Ack_Model_Contact extends System_Db_Table_AbstractRow
{
	protected $_table = "Reuse_Ack_Model_Contacts";
	
	public function getSectorObject()
	{
		$model = new Reuse_Ack_Model_Sectors;
		$result = $model->toObject()->get(array("setor"=>$this->getSetor()->getBruteVal()));
		$result = reset($result);
	
		if(empty($result))
			$result = new Reuse_Ack_Model_Sector;
	
		return $result;
	}

}
?>