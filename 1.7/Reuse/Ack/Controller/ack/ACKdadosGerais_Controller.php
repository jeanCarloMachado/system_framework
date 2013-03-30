<?php
	class ACKdadosGerais_Controller extends Reuse_Ack_Controller	
	{
		protected $modelName = "Reuse_Ack_Model_System";
		protected $title =  "Dados Gerais";
		
		protected $functionInfo = array(
				"editar"=> array("idiomas"=>true),
				"index"=> array("title"=>"Faq "),
				"global"=>array("visibleCol"=>"visible",
								"metatags"=>true,
								"disableBack"=>true,
								"metatagsExpansion"=>true
								));
		
		/**
		 * troca o estado de visível
		 * @throws Exception
		 */
		public function visivel()
		{
			$model = new Reuse_Ack_Model_Languages();
				
			$visible = $model->getVisibleCol();
			if(empty($visible))
				throw new Exception("Não há coluna visível disponível");
		
			$set = array();
			if((int)$this->ajax["status"] == $visible["enabled"])
				$set[$visible["name"]] = $visible["enabled"];
			else
				$set[$visible["name"]] = $visible["disabled"];
		
			$where = array("id"=>$this->ajax["id"]);
		
			$result = null;
		
			try {
				$result = $model->update($set,$where);
			} catch(Exception $e) {
				dg("a coluna visível não foi corretamente setada");
			}
		
			if($this->debug)
				sw($model->get());
		
			$result = array("status"=>1,"mensagem"=>"Visível alterado");
			echo json_encode($result);
		}
	}
?>
