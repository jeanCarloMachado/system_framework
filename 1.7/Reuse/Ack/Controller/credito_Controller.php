<?php
	class credito_Controller extends System_Controller
	{	
		public function planos()
		{
			$vars = array();
			
			$modelCreditPlans = new CreditPlans();
			$vars["plans"] = $modelCreditPlans->toObject()->onlyAvailable()->get(null,array("order"=>"credits_total DESC"));
			
			global $endereco_site;
			
			$modelHighlight = new Reuse_Ack_Model_Modules();
				
			/**
			 * pega o destaque
			*/
			$vars["row"] = $modelHighlight->onlyAvailable()->toObject()->get(array("id"=>Highlights::plansHighlightModuleId));
			$vars["row"] = reset($vars["row"]);
			
			
			
			
			return $vars;
		}
	}