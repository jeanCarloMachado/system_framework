<?php

	class faq_Controller extends System_Controller
	{
		public function index()
		{
			$modelFaq = new Faqs;
			$result = $modelFaq->get();
		
			$modelCategorys = new FaqCategorys;
			$vars["elements"] = $modelCategorys->toObject()->onlyAvailable()->get();
			
			
			$modelHighlight = new Reuse_Ack_Model_Modules();
			
			/**
			 * pega o destaque da pesquisa
			*/
			$vars["row"] = $modelHighlight->toObject()->get(array("id"=>Faqs::moduleId));
			
	
			$vars["row"] = reset($vars["row"]);
			
			return $vars;
		}
		
		public function ajax_utilidade()
		{
			$model = new Faqs;
			
			$where = array("id"=>(int)$this->ajax['id']);
			
			$result = $model->get($where);
			$result = reset($result);
			
			
			if($this->ajax["resposta"])
				$result= $model->update(array("utilityplus"=>($result["utilityplus"]+1)),$where);
			else 
				$result = $model->update(array("utilityless"=>($result["utilityless"]+1)),$where);
				
			echo json_encode(array("status"=>1,"mensagem"=>"Sua opinião foi passada à nós."));
		}
	}

?>