<?php
	/**
	 * OKA
	 */

   /*
	* DETALHES DOS CONTROLADORES msg v-1.1
	* OBS: os campos em colchetes não são obrigatórias os demais sim
	* ==============================================================================================
	*	$packageName
	* 		 nome do pacote recebido nas funções ajax
	* 	$modelName
	* 		 nome do modelo relacionado a esse controlador (o que irá disponibilizar os dados)
	*
	* ==============================================================================================
	*  [$functionInfo]: usado para mandar informações para as funções em tempo de execução
	*
	* 	SESSÕES:
	* 	[nomeDoMetodo],
	* 		 o que é incluso nesse array se a função com o mesmo nome que ela
	* 	[global]
	* 		 o que é incluso nesse array se aplica a todas as funções chamadas no controlador
	*
	* 		DENTRO DAS SESSÕES:
	* 			[categoryWhere]
	* 					array com as clausulas where para buscar a cetegoria (implementado em incluir e editar)
	* 			[where]
	* 		 			array com as clausulas where para buscar a cetegoria (carregar_mais e carregar_maisCategoria)
	* 			[disableVisible => 1]
	*					desabilita o campo visível das páginas internas (listagens ainda não foi implementado)
	*			[disableADDREMOVE => 1]
	*					faz desaparecer o campo adicionar e remover
	*			[returnFalseInCols = array()] 
	*				array de colunas do banco que devem retornar valor falso em vez de seu respectivo valor (usado para bloquear visíveis,status,etc
	* ==============================================================================================
	*/
	class ACKdestaques_Controller extends Reuse_Ack_Controller
	{
		protected $modelName = "Highlights";
		protected $categoryModelName = "Reuse_Ack_Model_Modules";
		
		/**
		 * id do destaque indeletável
		 * @var unknown
		 */
		const DELETELESS_HIGHLIGHT_ID = 113;
		
		protected $functionInfo = array(
				"editar"=> array("plugins"=>true,
						"multiplasImagens"=>true,
						"tamanhoCrop"=>"500 400",
						"abaImagens"=>true,
						"abaVideos"=>false,
						"abaAnexos"=>false),
				"incluir"=> array("plugins"=>true,
						"multiplasImagens"=>true,
						"tamanhoCrop"=>"500 400",
						"abaImagens"=>true,
						"abaVideos"=>false,
						"abaAnexos"=>false),
				
				"carregar_mais"=>array("returnFalseInCols"=>array("status","visivel")),
				
				"global"=>array(
									"categoryWhere"=>array("destaque"=>"1"),
									"DELETELESS_HIGHLIGHT_ID"=>self::DELETELESS_HIGHLIGHT_ID,
								)
		);
		
		/**
		 * função com comportamento diferenciado (por isso foi implementada aqui)
		 */
		protected function carregar_mais($parameters=null,$functionInfo=null)
		{
			
			//mudança aqui
			$categoryModel = null;
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
				
				if(!empty($this->categoryModelName)) {
					$tmpName = $this->categoryModelName;
					$categoryModel = new $tmpName;
				}
				
				foreach($result["grupo"] as $rowId => $row) {
					/**
					 * mudança de comportamento
					 */
					if($categoryModel) {
						$resultCategory = $categoryModel->toObject()->get(array("id"=>$row["modulo"]));
						$resultCategory = reset($resultCategory);
						$result["grupo"][$rowId]["modulo_titulo"] = (is_object($resultCategory)) ? $resultCategory->getTitulopt()->getVal() : "" ;
					}
					
					foreach($row as $elementId => $element) {
						
						if($functionInfo["returnFalseInCols"])
							//mudanças aqui
							if(in_array($elementId,$functionInfo["returnFalseInCols"]) && $row["id"] == self::DELETELESS_HIGHLIGHT_ID) {
								$result["grupo"][$rowId][$elementId] = "false";
								continue;
							}
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