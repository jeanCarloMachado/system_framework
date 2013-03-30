<?php
	
	class ACKinstitucional_Controller extends Reuse_Ack_Controller
	{
		
		protected $modelName = "Reuse_Ack_Model_Institutionals";
		protected $title = "Institucional";		
		
		
		protected $functionInfo = array(
										
										 "index"=> array("title"=>"Institucional","metatags"=>true),
										 "global"=>array("visibleCol"=>"visivel"),
 										 "editar"=>array("metatags"=>true),
										 "salvar"=>array("metatags"=>true)	 	
		);
		
	}
?>