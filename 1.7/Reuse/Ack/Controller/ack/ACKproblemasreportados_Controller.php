<?php
	class ACKproblemasreportados_Controller extends Reuse_Ack_Controller
	{
	 	protected $modelName = "Problems";
		protected $title = "Problemas reportados";
		
		protected $functionInfo = array(
		
				"global"=>array("disableStatus"=>true),
				"index"=> array("disableADD"=>true),
					
		);
	}