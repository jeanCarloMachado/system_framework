<?php
	class ACKdashboard_Controller extends Reuse_Ack_Controller
	{
		protected $modelName = "Reuse_Ack_Model_System";
		const DEFAULT_SYSTEM_ID = 1;
		
		protected $title = "Dashboard";
		
		protected $functionInfo = array(
				"global"=>array(
							"disableLoadMore"=>true,
								));
	}
?>