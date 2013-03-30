<?php

	class ACKimagens_Controller extends Reuse_Ack_Controller
	{
		protected $modelName = "Images";
		protected $title = "Imagens";
		protected $functionInfo = array(	
											"global"=>array(
																"disableStatus"=>true
															),
											"incluir"=>array(
																"plugins"=>true,
																"multiplasImagens"=>false,
																"tamanhoCrop"=>"500 400",
																"abaImagens"=>true,
																"abaVideos"=>false,
																"abaAnexos"=>false
															),		
											"editar"=>array(
																"plugins"=>true,
																"multiplasImagens"=>false,
																"tamanhoCrop"=>"500 400",
																"abaImagens"=>true,
																"abaVideos"=>false,
																"abaAnexos"=>false
															),
										);
		
		
		const IMAGE_OBSERVER = "Observer_ImageLog";
		
		
		
		public function getInstanceOfModel()
		{
			$modelName = $this->getModelName();
			$instance = new $modelName;
			$instance->attach(self::LOG_OBSERVER_NAME);
			$instance->attach(self::IMAGE_OBSERVER);
			return $instance;
		}
		
		
		/**
		 * {"acao":"save_novaTag","termo":"asdfasdf","id":"72"}
		 * executado depois de salvar os dados principais
		 * @param resultado do salvamento principal $result
		 */
		protected function beforeMainSave()
		{
			if($this->ajax["acao"] == "save_novaTag") {
				
				$result = null;
				$json = array();
				$imageId =& $this->ajax["id"];
				
				$modelTags = new Tags();
				$set =  array("value"=>$this->ajax["termo"]);
				$result = $modelTags->onlyNotDeleted()->get($set);
				
				if(empty($result)) {
					$result = $modelTags->create($set);
					
					$result = reset($result);
					
					if(empty($result)) {
						
					}
					
					//faz a relaão entre a imagem e a tag
					$modelImgTag = new ImageTags();
					$result = $modelImgTag->create(array("image_id"=>$imageId,"tag_id"=>$result));
				}
				
				if($result) {
					//prepara o retorno
					$json['status'] = 1;
					$json['id'] = $this->ajax[$package]["id"];
					$json["conteudoIdioma"] = array();
				}
				echo json_encode($json);
				die;
			}
		}
		
		/**
		 * executado depois de salvar os dados principais
		 * @param resultado do salvamento principal $result
		 */
		protected function afterMainSave(&$result)
		{
			$resultCpy = reset($result);
			$id = empty($resultCpy) ? $this->ajax[$this->getCleanClassName()]["id"] : $resultCpy;
			
			if(!empty($this->ajax["tamanhos"]["tamanhos"])) {
				/**
				 * salva os tamanhos disponíveis
				*/
				//remove todas as as relações atuais
				$model = new ImagesSizes();
				$model->delete(array("image_id"=>$id));
			
				$categorys =& $this->ajax["tamanhos"]["tamanhos"];
				//cria as associações (caso existam)
				if(!empty($categorys))
				{
					foreach($categorys as $category) {
						$model->create(array("image_id"=>$id,"size_id"=>$category));
					}
				}
			}
		}
		
		/**
		 * listagem de itens
		 */
		public function carregar_mais($parameters=null,$functionInfo=null)
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
			$result["grupo"] = array();
			if(!empty($resultObjects)) {
				foreach($resultObjects as $rowId => $row) {
					foreach($row->getVars() as $elementId => $element) {
							
						if($elementId == "id") {
							$tmp = strip_tags($element->getVal());
							$result["grupo"][$rowId]["id_image"] = System_Object_Number::putDigitsInfront($tmp);
						}
						
						if($functionInfo["returnFalseInCols"])
							if(in_array($elementId,$functionInfo["returnFalseInCols"])) {
							$result["grupo"][$rowId][$elementId] = "false";
							continue;
						}
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
		
		public function beforeReturn(&$functionInfo=null) 
		{
			/**
			 * adiciona as politicas e os status das imagens na consulta
			 */
			if(!$this->action == "editar" || $this->action == "incluir") {
				
				$modelPolicies = new Policies;
				$functionInfo["policies"] = $modelPolicies->toObject()->onlyAvailable()->get();
				
				$modelImageStatus = new ImageStatus();
				$functionInfo["imageStatus"] = $modelImageStatus->toObject()->onlyAvailable()->get();
			}
		}
		
		public function getCategoryData(&$functionInfo)
		{
			$row =& $functionInfo["row"];
			
			if($row->getId()->getBruteVal()) {
			
				$modelName = $this->getModelName();
				//pega o id da tag na tabela N-N			
				$modelImagesTags = new ImageTags();
				$resultImageTags = $modelImagesTags->get(array("image_id"=>$row->getId()->getBruteVal()));
	
				$result = array();
				$modelTags = new Tags();
				foreach($resultImageTags as $element) {
	
					$tmp =  $modelTags->onlyNotDeleted()->toObject()->get(array("id"=>$element["tag_id"]));
					if(!empty($tmp))
					$result[] = reset($tmp);
				}
				
				$functionInfo["myTags"] = $result;
				
				$functionInfo["sizes"] = $row->getAvailableSizes();
				
				
				$functionInfo["myComments"] = $row->getMyComments();
			}
						
		}
		
		public function ajax_enviarImagem()
		{
			global $endereco_fisico;
			//compatibilidade
			$dadosJSON =& $this->ajax;
			$id = $this->ajax["id"];
			
			//Pega o ID do módulo
			$modelSite=new ACKsite_Model();
			$modulo=$modelSite->idModulo($dadosJSON["modulo"]);
			
			$modelName = $this->getModelName();
			$model = new $modelName;
				
			//Pega os dados da imagem antiga
			$modelUploads=new ACKuploads_Model();
			$dadosImgAntiga=$modelUploads->dataMidia("fotos", array("modulo"=>$modulo,"relacao_id"=>$dadosJSON['id'], "status"=>"1"));
	
			//Cria um array com o nome do arquivo gravado no server e nome original do arquivo enviado
			$dados_arquivo=explode("|cub|",$dadosJSON['arquivos'][0]);
	
			//Gera o array final com os dados para salvar no banco
			$dadosFoto["resultados"]=array("arquivo"=>$dados_arquivo[0],"modulo"=>$modulo,"relacao_id"=>$dadosJSON["id"],"ordem"=>"1");
	
			// Passa todos os idiomas enviados pelo JSON para preencher o campo de visível e o título do arquivo
			foreach ($dadosJSON["idiomas"] as $idioma => $visivel) {
				$dadosFoto["resultados"]["titulo_".$idioma]=$dados_arquivo[1];
				$dadosFoto["resultados"]["visivel_".$idioma]=$dadosJSON["idiomas"][$idioma];
			}	
			
			//Define o ID da Foto que está sendo atualizada
			$dadosFoto["resultados"]["id"]=$dadosImgAntiga['id'];
			//Apaga o arquivo da imagem antiga
			unlink($endereco_fisico."/galeria/".$dadosImgAntiga["arquivo"]);
				
			//Faz o update no banco
			$tmpSet = ($dadosFoto["resultados"]);
			//conversao dos dados
			$set =  array();
			$set["file"] = $tmpSet["arquivo"];
			$set["title"] = $tmpSet["titulo_pt"];
			
			$image_path = "galeria/".$set["file"];
			$image = new System_Object_Image($image_path);
			$result = Helper_SetupImage::run($image,$id);
			
			//Pega os dados do Crop para excluir
			$dadosCropAntigo=$modelUploads->dataMidia("crops", array("relacao_id"=>$dadosImgAntiga['id'], "modulo"=>$modulo));
			if ($dadosCropAntigo["id"]!="0") {
				//Forma os dados de atualização do CROP
				$dadosCrop["resultados"]["altura"]="0";
				$dadosCrop["resultados"]["largura"]="0";
				$dadosCrop["resultados"]["x"]="0";
				$dadosCrop["resultados"]["y"]="0";
				$dadosCrop["resultados"]["id"]=$dadosCropAntigo['id'];
				//Faz o update no banco
				dbUpdate("crops", $dadosCrop);
			}
			$this->afterSendImage();
			$retorno['status']   = '1';
			$retorno['mensagem'] = 'Imagens enviadas com sucesso.';
			echo newJSON($retorno);
			die;
		}
		/**
		 * carrega os arquivos
		 */
		public function ajax_carregarArquivos()
		{
			$dadosJSON =& $this->ajax;
			global $endereco_site;
			//Verifica se o tipo de arquivo enviado era imagem, vídeo ou anexo

			//Pega os idiomas do site
				$modelSite=new ACKsite_Model();
				$idioma=$modelSite->idiomasSite(1);
				$modulo=$modelSite->idModulo($dadosJSON["modulo"]);
		
				//Pega as imagens do módulo
				$modelImage = new Images;
				$image=$modelImage->onlyNotDeleted()->toObject()->get(array("id"=>$this->ajax["id"]));
				$image = reset($image);
				
				if ($image) {
						$tamanho = tamanhoArquivo("/galeria/".$image->getFile()->getVal());
						$caminhoImagem=Reuse_ACK_View_Helper_Show_Image::runImgImage($image); //(->getId()->getVal(),"114","85")
						$retorno['galeria'][$image->getId()->getVal()]=array('arquivo'=>$caminhoImagem, 'titulo'=>$image->getTitle()->getVal(), 'ordem'=>$image->getOrdem()->getVal(),  'tamanho'=>$tamanho);
				} else {
					$retorno['galeria']="";
				}
			$retorno['status']   = '1';
			$retorno['mensagem'] = 'Arquivos carregados com sucesso.';
			echo newJSON($retorno);
		}
	}
?>