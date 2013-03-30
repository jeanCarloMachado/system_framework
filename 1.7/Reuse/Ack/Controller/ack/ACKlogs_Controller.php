<?php
	class ACKlogs_Controller extends Reuse_Ack_Controller	
	{
		protected $modelName = "Reuse_Ack_Model_Logs";
		protected $title = "Logs";		
		protected $functionInfo = array("global"=>array("disableADDREMOVE" => 1));
		
		
		/**
		 * listagem de itens
		 */
		protected function carregar_mais($parameters=null,$functionInfo=null)
		{
		
			$modelSite=new ACKsite_Model();
			$dadosSite=$modelSite->dadosSite();
			$limite=$dadosSite["itens_pagina"];
				
			$modelName = $this->getModelName();
			$model =  new $modelName;
		
			$result["grupo"] = $model->onlyNotDeleted()->get($functionInfo["where"],array("limit"=>array("offset"=>$this->ajax["qtd_itens"],"count"=>$limite),"order"=>"id DESC"));
		
			/**
			 * remove os elementos html das strings
			*/
			if(!empty($result["grupo"][0])) {
				foreach($result["grupo"] as $rowId => $row) {
					foreach($row as $elementId => $element) {

						
							
						
						if($functionInfo["returnFalseInCols"])
							if(in_array($elementId,$functionInfo["returnFalseInCols"])) {
							$result["grupo"][$rowId][$elementId] = "false";
							continue;
						}
						
						if($elementId == "id")
						$result["grupo"][$rowId]["coluna_id"] = System_Object_Number::putDigitsInfront($element);
						
						$result["grupo"][$rowId][$elementId] = strip_tags($element);
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
