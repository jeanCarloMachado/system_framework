<?php
	class ACKcontatos_Controller extends Reuse_Ack_Controller
	{
		protected $packageName = "contato";
		protected $modelName = "Contacts";
		protected $title = "Contato";
		protected $functionInfo = array("global"=>array("sprint1"=>false),
										"editar"=>array("disableLayout"=>true));
		
		
		protected function carregar_mais($parameters=null,$functionInfo=null)
		{
			//pega o nome do modelo dependendo se é categoria ou não
			$model = (empty($this->ajax["isCategory"])) ? $this->getInstanceOfModel() : $this->getInstanceOfCategoryModel();
				
			$modelSite=new ACKsite_Model();
			$dadosSite=$modelSite->dadosSite();
			$limite=$dadosSite["itens_pagina"];
				
			$resultObjects = $model->toObject()->onlyNotDeleted()->get($functionInfo["where"],array("limit"=>array("offset"=>$this->ajax["qtd_itens"],"count"=>$limite),"order"=>"id DESC"));
			/**
			 * remove os elementos html das strings
			*/
			//MODELO DE SETOR
			$modelSector = new Sectors;
			
			$result["grupo"] = array();
			if(!empty($resultObjects)) {
				foreach($resultObjects as $rowId => $row) {
					foreach($row->getVars() as $elementId => $element) {
						
						if($elementId  == "setor") {
							$resultSector = $modelSector->toObject()->get(array("id"=>$element->getVal()));
							$resultSector = reset($resultSector);

							
							$element = null;	
							if($resultSector)
							$result["grupo"][$rowId][$elementId] = strip_tags($resultSector->getTituloPt()->getVal());
						}
							
						if($functionInfo["returnFalseInCols"])
							if(in_array($elementId,$functionInfo["returnFalseInCols"])) {
							$result["grupo"][$rowId][$elementId] = "false";
							continue;
						}
						
						if($element)	
						$result["grupo"][$rowId][$elementId] = strip_tags($element->getVal());
					}
				}
			}
		
			if (count($result["grupo"]) < $limite) {
				$result['exibir_botao'] = 0;
			} else {
				$result['exibir_botao'] = 1;
			}
			echo json_encode($result);
		}
	}
?>