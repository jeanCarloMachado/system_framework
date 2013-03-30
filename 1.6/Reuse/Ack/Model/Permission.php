<?php
//namespace System;

class Reuse_Ack_Model_Permission extends System_Db_Table_AbstractRow
{
	protected $_table = "Reuse_Ack_Model_Permissions";

	public function getRelatedUserObject()
	{
		$model = new Reuse_Ack_Model_AckUsers();
		$result = $model->toObject()->get(array("id"=>$this->getUsuario()->getBruteVal()));

		$result = reset($result);
		
		if(empty($result))
			$result = new Reuse_Ack_Model_AckUser();
		
		return $result;
	}
}
?>