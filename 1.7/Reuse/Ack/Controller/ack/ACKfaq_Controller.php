<?php
	/**
	 * OKA
	 * @author jean
	 *
	 */
	class ACKfaq_Controller extends Reuse_Ack_Controller
	{
		
		protected $modelName = "Faqs";
		protected $categoryModelName = "FaqCategorys";
		//protected $debug = 1;
		
		protected $title = "FAQ";
		
		protected $functionInfo = array(
										"editar"=> array("plugins"=>true,
												"multiplasImagens"=>true,
												"tamanhoCrop"=>"500 400",
												"abaImagens"=>true,
												"abaVideos"=>false,
												"abaAnexos"=>false),
										 "index"=> array("title"=>"Faq "),
										 "categorias"=> array("title"=>"Categorias de Faqs"),
											"global"=>array()
				);
		
		/**
		 * nesta função foi mudada a ordem da categoria	
		 * listagem de itens
		 */
		protected function carregar_mais($parameters=null,$functionInfo=null)
		{
			//pega o nome do modelo dependendo se é categoria ou não
			$model = (empty($this->ajax["isCategory"])) ? $this->getInstanceOfModel() : $this->getInstanceOfCategoryModel();
				
			$modelSite=new ACKsite_Model();
			$dadosSite=$modelSite->dadosSite();
			$limite=$dadosSite["itens_pagina"];
				
		
			$set = null;
			if(!$functionInfo["isCategory"])
				$set = array("limit"=>array("offset"=>$this->ajax["qtd_itens"],"count"=>$limite),"order"=>"category_id asc");
			$resultObjects = $model->toObject()->onlyNotDeleted()->get($functionInfo["where"],$set);
			/**
			 * remove os elementos html das strings
			*/
			$result["grupo"] = array();
			if(!empty($resultObjects)) {
				foreach($resultObjects as $rowId => $row) {
					foreach($row->getVars() as $elementId => $element) {
							
						if($functionInfo["returnFalseInCols"])
							if(in_array($elementId,$functionInfo["returnFalseInCols"])) {
							$result["grupo"][$rowId][$elementId] = "false";
							continue;
						}
						if($elementId == "id")
							$result["grupo"][$rowId][$elementId] = strip_tags($element->getBruteVal());
						else
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