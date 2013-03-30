<?php

	class ACKtipodelicenca_Controller extends Reuse_Ack_Controller
	{
		protected $modelName = "Policies";
		protected $title = "Tipos de Licença";
		
		protected $functionInfo = array (
									"global"=> array(
														"disableADDRemoveMenu"=>true,
														"disableStatus"=>true,
													)
								);
	}
?>