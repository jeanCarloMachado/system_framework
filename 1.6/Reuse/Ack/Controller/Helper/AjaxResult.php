<?php 

	class Reuse_Ack_Controller_Helper_AjaxResult 
	{
		public static function run($result,$array=null)
		{
			if(empty($array)) {
				
				if(empty($result)) 
						$array = array("status"=>0,"mensagem"=>System_Language::translate("Falha no processo"));
				else
						$array = array("status"=>1,"resultados"=>$result);
				
			}
			echo json_encode($array);
			die;
		}
	}
?>