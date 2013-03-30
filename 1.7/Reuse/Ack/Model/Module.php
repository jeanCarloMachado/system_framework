
<?php
//namespace System;

class Reuse_Ack_Model_Module extends System_Db_Table_AbstractRow
{
	protected $_table = "Reuse_Ack_Model_Modules";
	
	public function getUserPermissionLevel($userId = null)
	{
		if(!$this->getId()->getBruteVal())
			return false;
		
		$where =  array("modulo"=>$this->getId()->getBruteVal());
		
		if(!$userId) {
			$auth = new Reuse_Ack_Auth_BackUser();
			$where["usuario"]=$auth->getUserObject()->getId()->getBruteVal();
		} else {
			$where["usuario"]=$userId;
		}
		
		$result = null;
		{
			$modelPermissions = new Reuse_Ack_Model_Permissions();
			$result = $modelPermissions->get($where);
			$result = reset($result);
		}
		
		return $result["nivel"];
	}
}
?>