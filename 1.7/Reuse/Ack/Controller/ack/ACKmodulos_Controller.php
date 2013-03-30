<?php
	class ACKmodulos_Controller extends Reuse_Ack_Controller
	{
		protected $modelName = "Reuse_Ack_Model_Modules";
// 		protected $categoryModelName = "FaqCategorys";
//      protected $debug = 1;
		
		protected $title = "Modulo";
		protected $functionInfo = array(				
										 "index"=> array("title"=>"modulos","disableADDREMOVE"=>true,"disableVisible"=>true),
										 "carregar_mais"=>array("where"=>array("destaque"=>"0","ack"=>"0")),
										 "global"=>array("disableVisible"=>1,"metatags"=>true)
										);
	}
?>