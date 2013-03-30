<?php

	class ACKsetoresdecompra_Controller extends Reuse_Ack_Controller
	{
		protected $modelName = "IMGSectors";
		protected $title = "Setores de negócio";
		
		protected $functionInfo = array(
											"global"=>array(
														"disableStatus"=>true,	
														"disableVisible"=>false,
														"disableColB"=>false,
														"disableADDRemoveMenu"=>false
													),
											"incluir"=>array(
														//id1 é o defaut o qual os outros devem se basear
														"percentageDefaultVal"=>1
													)
										);
	}
?>