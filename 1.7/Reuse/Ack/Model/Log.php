<?php

	class Reuse_Ack_Model_Log extends System_Db_Table_AbstractRow
	{
		protected $_table = "Reuse_Ack_Model_Logs";
		
		public function getUsuarioObject()
		{
			$modelUser = new Reuse_Ack_Model_AckUsers();
			$resultUser = $modelUser->toObject()->get(array("id"=>$this->getusuario()->getBruteVal()));
			
			$resultUser = reset($resultUser);
			if(empty($resultUser))
				return new Reuse_Ack_Model_AckUser();
			
			return $resultUser;
		}
	}
?>