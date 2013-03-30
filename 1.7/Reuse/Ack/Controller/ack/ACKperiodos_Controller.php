<?php

	class ACKperiodos_Controller extends Reuse_Ack_Controller
	{
		protected $modelName = "Periods";
		protected $title = "Períodos";
		
		protected $functionInfo = array(
												"editar"=>array("disableStatus"=>true),
												"incluir"=>array("disableStatus"=>true),
											"global"=>array(
														"disableStatus"=>false,	
														"disableVisible"=>false,
														"disableColB"=>false,
														"disableADDRemoveMenu"=>false
													)
									
										);
	}
?>