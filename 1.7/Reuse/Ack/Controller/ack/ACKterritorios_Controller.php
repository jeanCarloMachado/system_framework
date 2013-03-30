<?php

	class ACKterritorios_Controller extends Reuse_Ack_Controller
	{
		protected $modelName = "Regions";
		protected $title = "Territórios";
		
		protected $functionInfo = array(
											"editar"=>array("disableStatus"=>true),
											"incluir"=>array("disableStatus"=>true),
											"global"=>array(
														"disableStatus"=>true,	
														"disableVisible"=>false,
														"disableColB"=>false,
														"disableADDRemoveMenu"=>false
													)
									
										);
	}
?>