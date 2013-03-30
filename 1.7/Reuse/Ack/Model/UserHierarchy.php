
<?php
//namespace System;

class UserHierarchy extends System_Db_Table_AbstractRow
{
	protected $_table = "UserHierarchys";
	
	public function getSlave()
	{
		$id = $this->getSlaveId()->getBruteVal();
		if(empty($id))
			return null;
		
		$modelUser = new Users;
		$where = array("id"=>$this->getSlaveId()->getBruteVal());
		
		$result = $modelUser->onlyAvailable()->toObject()->get($where);
		
		if(empty($result))
			return null;
		
		$result = reset($result);
		
		return $result;
	}
}
?>