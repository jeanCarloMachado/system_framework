<?php
	class sobre_Controller extends System_Controller
	{	
		public function politica()
		{
			$vars = array();

			$modelHighlight = new Reuse_Ack_Model_Modules();
				
			/**
			 * pega o destaque da pesquisa
			*/
			$vars["row"] = $modelHighlight->onlyAvailable()->toObject()->get(array("id"=>Reuse_Ack_Model_Modules::politicaId));
		
			$vars["row"] = reset($vars["row"]);
			
			return $vars;
			
		}
		
		public function termos()
		{
			$vars = array();

			$modelHighlight = new Reuse_Ack_Model_Modules();
				
			/**
			 * pega o destaque da pesquisa
			*/
			$vars["row"] = $modelHighlight->onlyAvailable()->toObject()->get(array("id"=>Reuse_Ack_Model_Modules::termosId));
			$vars["row"] = reset($vars["row"]);
			
			return $vars;
				
		}
		
	}