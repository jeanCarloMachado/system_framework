<?php
class ACKuploads_Controller
{
    function index () {
		postRequest();
		protectedArea();
		openSession();
		verifyTimeSession();
		$dadosJSON=readJSON($_POST["ajaxACK"]);
		$acao=$dadosJSON["acao"];
		$this->$acao($dadosJSON);
	}
	function enviar_arquivos($dadosJSON) {	
		global $endereco_fisico;
		//Pega o ID do módulo
		$modelSite=new ACKsite_Model();
		$modulo=$modelSite->idModulo($dadosJSON["modulo"]);
		
		//Verifica se o tipo de arquivo enviado era imagem, vídeo ou anexo
		if($dadosJSON['tipo'] == 'imagem' ){
			if ($dadosJSON['multi']=="false") {
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
							
				//Apaga a imagem antiga, caso exista
				if ($dadosImgAntiga["arquivo"]) {
					//Define o ID da Foto que está sendo atualizada
					$dadosFoto["resultados"]["id"]=$dadosImgAntiga['id'];
					//Faz o update no banco
					dbUpdate("fotos", $dadosFoto);
					//Apaga o arquivo da imagem antiga
					unlink($endereco_fisico."/galeria/".$dadosImgAntiga["arquivo"]);
					
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
				} else {
					//Salva no banco
					dbSave("fotos", $dadosFoto);
				}
			} else {
				//Passa todos os arquivos enviados pelo array do JSON
				foreach ($dadosJSON['arquivos'] as $arquivo) {
					//Pega a próxima ordem de arquivo, colocando ele sempre no final
					$ordem = proximaOrdem("fotos", array("modulo"=>$modulo,"relacao_id"=>$dadosJSON["id"],"status"=>"1"));

					//Cria um array com o nome do arquivo gravado no server e nome original do arquivo enviado
					$dados_arquivo=explode("|cub|",$arquivo);

					//Gera o array final com os dados para salvar no banco		
					$dadosFoto["resultados"]=array("arquivo"=>$dados_arquivo[0],"modulo"=>$modulo,"relacao_id"=>$dadosJSON["id"],"ordem"=>$ordem);

					// Passa todos os idiomas enviados pelo JSON para preencher o campo de visível e o título do arquivo
					foreach ($dadosJSON["idiomas"] as $idioma => $visivel) {
						$dadosFoto["resultados"]["titulo_".$idioma]=$dados_arquivo[1];
						$dadosFoto["resultados"]["visivel_".$idioma]=$visivel;
					}

					//Salva no banco
					dbSave("fotos", $dadosFoto);
				}
			}
			$retorno['status']   = '1';
			$retorno['mensagem'] = 'Imagens enviadas com sucesso.';
		} else if ($dadosJSON['tipo'] == 'video' ){
			//Passa todos os arquivos enviados pelo array do JSON
			foreach ($dadosJSON['arquivos'] as $arquivo) {
				//Pega a próxima ordem de arquivo, colocando ele sempre no final
				$ordem = proximaOrdem("videos", array("modulo"=>$modulo,"relacao_id"=>$dadosJSON["id"],"status"=>"1"));

				//Cria um array com o nome do arquivo gravado no server e nome original do arquivo enviado
				$dados_arquivo=explode("|cub|",$arquivo);

				//Gera o array final com os dados para salvar no banco		
				$dadosVideo["resultados"]=array("arquivo"=>$dados_arquivo[0],"modulo"=>$modulo,"relacao_id"=>$dadosJSON["id"],"ordem"=>$ordem,"tipo"=>"3");

				// Passa todos os idiomas enviados pelo JSON para preencher o campo de visível e o título do arquivo
				foreach ($dadosJSON["idiomas"] as $idioma => $visivel) {
					$dadosVideo["resultados"]["titulo_".$idioma]=$dados_arquivo[1];
					$dadosVideo["resultados"]["visivel_".$idioma]=$visivel;
				}

				//Salva no banco
				dbSave("videos", $dadosVideo);
			}
			$retorno["status"]   = '1';
			$retorno['mensagem'] = 'Videos enviados com sucesso.';
		} else if ($dadosJSON['tipo'] == 'anexo' ){
			//Passa todos os arquivos enviados pelo array do JSON
			foreach ($dadosJSON['arquivos'] as $arquivo) {
				//Pega a próxima ordem de arquivo, colocando ele sempre no final
				$ordem = proximaOrdem("anexos", array("modulo"=>$modulo,"relacao_id"=>$dadosJSON["id"],"status"=>"1"));

				//Cria um array com o nome do arquivo gravado no server e nome original do arquivo enviado
				$dados_arquivo=explode("|cub|",$arquivo);

				//Gera o array final com os dados para salvar no banco		
				$dadosAnexo["resultados"]=array("arquivo"=>$dados_arquivo[0],"modulo"=>$modulo,"relacao_id"=>$dadosJSON["id"],"ordem"=>$ordem,"status"=>"1");

				// Passa todos os idiomas enviados pelo JSON para preencher o campo de visível e o título do arquivo
				foreach ($dadosJSON["idiomas"] as $idioma => $visivel) {
					$dadosAnexo["resultados"]["titulo_".$idioma]=$dados_arquivo[1];
					$dadosAnexo["resultados"]["visivel_".$idioma]=$visivel;
				}

				//Salva no banco
				dbSave("anexos", $dadosAnexo);
			}
			$retorno["status"]   = '1';
			$retorno['mensagem'] = 'Anexos enviados com sucesso.';
		}
		echo newJSON($retorno);
	}
	function carregar_arquivos($dadosJSON) {
		global $endereco_site;
		//Verifica se o tipo de arquivo enviado era imagem, vídeo ou anexo
		if($dadosJSON['tipo'] == 'imagem' ){
			//Pega os idiomas do site
			$modelSite=new ACKsite_Model();
			$idioma=$modelSite->idiomasSite(1);
			$modulo=$modelSite->idModulo($dadosJSON["modulo"]);

			//Pega as imagens do módulo
			$modelUploads=new ACKuploads_Model();
			$imagens=$modelUploads->listaImagens("fotos",array("modulo"=>$modulo, "relacao_id"=>$dadosJSON['id'], "status"=>"1"));
			if ($imagens) {
				foreach ($imagens as $imagem) {
					$tamanho = tamanhoArquivo("/galeria/".$imagem["arquivo"]);
					$caminhoImagem=mostraImagem($imagem["id"],"114","85",false,false,"FAFAFA");
					$retorno['galeria'][$imagem["id"]]=array('arquivo'=>$caminhoImagem, 'titulo'=>$imagem["titulo_".$idioma[0]["abreviatura"]], 'ordem'=>$imagem["ordem"],  'tamanho'=>$tamanho);
				}
			} else {
				$retorno['galeria']="";
			}
		} else if ($dadosJSON['tipo'] == 'video' ){
			//Pega os idiomas do site
			$modelSite=new ACKsite_Model();
			$idioma=$modelSite->idiomasSite(1);
			$modulo=$modelSite->idModulo($dadosJSON["modulo"]);

			//Pega as imagens do módulo
			$modelUploads=new ACKuploads_Model();
			$videos=$modelUploads->listaImagens("videos",array("modulo"=>$modulo, "relacao_id"=>$dadosJSON['id'], "status"=>"1"));
			if ($videos) {
				foreach ($videos as $video) {
					if ($video["tipo"]=="3") {
						$moduloVerifica=$modelSite->idModulo("videos_ack",true);
						
						$thumbVideo=$modelUploads->dataMidia("fotos", array("modulo"=>$moduloVerifica, "relacao_id"=>$video["id"], "status"=>"1"));
						if ($thumbVideo) {
							$thumb=mostraImagem($thumbVideo["id"],"114","85",false,false,"FAFAFA");
						} else {
							$thumb=$endereco_site."/imagens/ack/galeriaVideo_bg.jpg";
						}
						$retorno['galeria'][$video["id"]]=array('arquivo'=>$thumb, 'titulo'=>$video["titulo_".$idioma[0]["abreviatura"]], 'ordem'=>$video["ordem"],  'tamanho'=>'');
					} elseif ($video["tipo"]=="2") {
						$thumb=vimeoThumb($video["arquivo"]);
						$retorno['galeria'][$video["id"]]=array('arquivo'=>$thumb, 'titulo'=>$video["titulo_".$idioma[0]["abreviatura"]], 'ordem'=>$video["ordem"],  'tamanho'=>'');
					} elseif ($video["tipo"]=="1") {
						$thumb=youtubeThumb($video["arquivo"]);
						$retorno['galeria'][$video["id"]]=array('arquivo'=>$thumb, 'titulo'=>$video["titulo_".$idioma[0]["abreviatura"]], 'ordem'=>$video["ordem"],  'tamanho'=>'');
					}
				}
			} else {
				$retorno['galeria']="";
			}
		} else if ($dadosJSON['tipo'] == 'anexo' ){
			//Pega os idiomas do site
			$modelSite=new ACKsite_Model();
			$idioma=$modelSite->idiomasSite(1);
			$modulo=$modelSite->idModulo($dadosJSON["modulo"]);

			//Pega as imagens do módulo
			$modelUploads=new ACKuploads_Model();
			$anexos=$modelUploads->listaImagens("anexos",array("modulo"=>$modulo, "relacao_id"=>$dadosJSON['id'], "status"=>"1"));
			if ($anexos) {
				foreach ($anexos as $anexo) {
					$tamanho = tamanhoArquivo("/galeria/anexos/".$anexo["arquivo"]);
					$retorno['galeria'][$anexo["id"]]=array('arquivo'=>$anexo["arquivo"], 'titulo'=>$anexo["titulo_".$idioma[0]["abreviatura"]], 'ordem'=>$anexo["ordem"],  'tamanho'=>$tamanho);
				}
			} else {
				$retorno['galeria']="";
			}
		}
		$retorno['status']   = '1';
		$retorno['mensagem'] = 'Arquivos carregados com sucesso.';
		echo newJSON($retorno);
	}
	function excluir_itemUpload($dadosJSON) {
		global $endereco_fisico;
		$modelSite=new ACKsite_Model();
		$modulo=$modelSite->idModulo($dadosJSON["modulo"]);

		//Verifica se o tipo de arquivo enviado era imagem, vídeo ou anexo
		if($dadosJSON['tipo'] == 'imagem' ){
			$modelUploads=new ACKuploads_Model();
			//Pega os dados da imagem antiga
			$dadosImgAntiga=$modelUploads->dataMidia("fotos", array("id"=>$dadosJSON['id_arquivo']));
			
			//Apaga a imagem antiga, caso exista
			if ($dadosImgAntiga["arquivo"]) {
				unlink($endereco_fisico."/galeria/".$dadosImgAntiga["arquivo"]);
			}

			dbDelete("fotos", $dadosJSON['id_arquivo']);
		
			//Pega os dados do Crop para excluir
			$dadosCrop=$modelUploads->dataMidia("crops", array("relacao_id"=>$dadosImgAntiga['id'], "modulo"=>$modulo));

			//Apaga o registro na tabela de crops
			dbDelete("crops", $dadosCrop['id'], false, false);
			
			//Retorno do JSON
			$retorno['id'] = $dadosJSON['id_arquivo'];
			$retorno['status'] = '1';
			$retorno['mensagem'] = 'Arquivo excluído com sucesso.';
		} else if ($dadosJSON['tipo'] == 'video' ){
			$modelUploads=new ACKuploads_Model();
			
			$moduloVerifica=$modelSite->idModulo("videos_ack",true);
			
			//Pega os dados do thumb do vídeo
			$dadosImgAntiga=$modelUploads->dataMidia("fotos", array("modulo"=>$moduloVerifica, "relacao_id"=>$dadosJSON['id_arquivo'], "status"=>"1"));
			
			//Apaga a imagem antiga, caso exista
			if ($dadosImgAntiga["arquivo"]) {
				unlink($endereco_fisico."/galeria/".$dadosImgAntiga["arquivo"]);
				//Apaga do banco o thumb
				dbDelete("fotos", $dadosImgAntiga["id"]);
			}

			//Pega os dados do thumb do vídeo
			$dadosVideo=$modelUploads->dataMidia("videos", array("id"=>$dadosJSON['id_arquivo']));
			
			//Apaga a imagem antiga, caso exista
			if ($dadosVideo["arquivo"] and $dadosVideo["tipo"]=="3") {
				unlink($endereco_fisico."/galeria/videos/".$dadosVideo["arquivo"]);
			}
		
			//Apaga o registro na tabela de crops
			dbDelete("videos", $dadosJSON['id_arquivo']);
			
			//Retorno do JSON
			$retorno['id'] = $dadosJSON['id_arquivo'];
			$retorno['status'] = '1';
			$retorno['mensagem'] = 'Arquivo excluído com sucesso.';
		} else if ($dadosJSON['tipo'] == 'anexo' ){
			$modelUploads=new ACKuploads_Model();

			//Pega os dados do thumb do vídeo
			$dadosAnexo=$modelUploads->dataMidia("anexos", array("id"=>$dadosJSON['id_arquivo']));
			
			//Apaga a imagem antiga, caso exista
			if ($dadosAnexo["arquivo"]) {
				unlink($endereco_fisico."/galeria/anexos/".$dadosAnexo["arquivo"]);
			}
		
			//Apaga o registro na tabela de crops
			dbDelete("anexos", $dadosJSON['id_arquivo']);
			
			//Retorno do JSON
			$retorno['id'] = $dadosJSON['id_arquivo'];
			$retorno['status'] = '1';
			$retorno['mensagem'] = 'Arquivo excluído com sucesso.';
		}
		echo newJSON($retorno);
	}
	function editar($dadosJSON) {
		global $endereco_fisico;
		global $endereco_site;
		//Pega o ID do módulo
		$modelSite=new ACKsite_Model();
		$modulo=$modelSite->idModulo($dadosJSON["modulo"]);

		//Verifica se o tipo de arquivo enviado era imagem, vídeo ou anexo
		if($dadosJSON['tipo'] == 'imagem' ){
			//Pega os dados da imagem antiga
			$modelUploads=new ACKuploads_Model();
			$dadosImagem=$modelUploads->dataMidia("fotos", array("id"=>$dadosJSON['id']));

			//Pega os idiomas do site
			$idiomasSite=$modelSite->idiomasSite();
				
			//Pega os dados do Crop
			$dadosCrop=$modelUploads->dataMidia("crops", array("relacao_id"=>$dadosImagem['id'], "modulo"=>$modulo));
			
			//Pega os idiomas do site já monta o retorno do array
			$idiomas=array();
			foreach ($idiomasSite as $idioma) {
				$idiomas[$idioma["abreviatura"]]=array('titulo'=>$dadosImagem['titulo_'.$idioma["abreviatura"]], 'legenda'=>$idioma["nome"].' - '.strtoupper($idioma["abreviatura"]), 'visivel'=>$dadosImagem['visivel_'.$idioma["abreviatura"]]);
			}
			
			//Pega o tamanho em pixels da imagem
			$tamanho=tamanhoImagem($endereco_fisico."/galeria/".$dadosImagem["arquivo"]);
					
			$retorno['dados_arquivo'] = array(
				'id'=>$dadosImagem["id"],
				'arquivo'=>$endereco_site."/galeria/".$dadosImagem["arquivo"],
				'larguraIMG'=>$tamanho["largura"],
				'alturaIMG'=>$tamanho["altura"],
				'crop'=>$dadosCrop["id"],
				'larguraCrop'=>$dadosCrop["largura"],
				'alturaCrop'=>$dadosCrop["altura"],
				'posicaoX'=>$dadosCrop["x"],
				'posicaoY'=>$dadosCrop["y"],
				'idiomas'=>$idiomas
			);
		} elseif( $dadosJSON['tipo'] == 'video' ){
			//Pega os dados da imagem antiga
			$modelUploads=new ACKuploads_Model();
			$dadosVideo=$modelUploads->dataMidia("videos", array("id"=>$dadosJSON['id']));

			//Pega os idiomas do site
			$idiomasSite=$modelSite->idiomasSite();
		
			//Pega os idiomas do site já monta o retorno do array
			$idiomas=array();
			foreach ($idiomasSite as $idioma) {
				$idiomas[$idioma["abreviatura"]]=array('titulo'=>$dadosVideo['titulo_'.$idioma["abreviatura"]], 'legenda'=>$idioma["nome"].' - '.strtoupper($idioma["abreviatura"]), 'visivel'=>$dadosVideo['visivel_'.$idioma["abreviatura"]]);
			}
			if ($dadosVideo["tipo"]=="3") {
				$moduloVerifica=$modelSite->idModulo("videos_ack",true);
				
				$thumbVideo=$modelUploads->dataMidia("fotos", array("modulo"=>$moduloVerifica, "relacao_id"=>$dadosVideo["id"], "status"=>"1"));
				if ($thumbVideo) {
					$url="../../galeria/".$thumbVideo["arquivo"];	
				} else {
					$url="../../imagens/ack/previewThumb.jpg";
				}
			} elseif ($dadosVideo["tipo"]=="2") {
				$url=$dadosVideo["arquivo"];
			} elseif ($dadosVideo["tipo"]=="1") {
				$url=$dadosVideo["arquivo"];
			}
						
			$retorno['dados_arquivo'] = array(
				'id'=>$dadosVideo["id"],
				'url'=>$url,
				'idiomas'=>$idiomas
			);
		} elseif( $dadosJSON['tipo'] == 'anexo' ){
			//Pega os dados da imagem antiga
			$modelUploads=new ACKuploads_Model();
			$dadosAnexo=$modelUploads->dataMidia("anexos", array("id"=>$dadosJSON['id']));

			//Pega os idiomas do site
			$idiomasSite=$modelSite->idiomasSite();
		
			//Pega os idiomas do site já monta o retorno do array
			$idiomas=array();
			foreach ($idiomasSite as $idioma) {
				$idiomas[$idioma["abreviatura"]]=array('titulo'=>$dadosAnexo['titulo_'.$idioma["abreviatura"]], 'legenda'=>$idioma["nome"].' - '.strtoupper($idioma["abreviatura"]), 'visivel'=>$dadosAnexo['visivel_'.$idioma["abreviatura"]]);
			}
			
			//Pega o tamanho do arquivo
			$tamanho = tamanhoArquivo("/galeria/anexos/".$dadosAnexo["arquivo"]);
		
			$retorno['dados_arquivo'] = array(
				'id'=>$dadosAnexo["id"],
				'nome'=>$dadosAnexo["arquivo"],
				'tamanho'=>$tamanho,
				'idiomas'=>$idiomas
			);
		}
		$retorno['status'] = '1';
		$retorno['mensagem'] = 'Dados recebidos';
		echo newJSON($retorno);
	}
	function salvar($dadosJSON) {
		global $endereco_fisico;
		global $endereco_site;
		//Pega o ID do módulo
		$modelSite=new ACKsite_Model();
		$modulo=$modelSite->idModulo($dadosJSON["modulo"]);
	
		if( $dadosJSON['tipo'] == 'imagem' ){
			//Informações referentes aos dados da tabela de fotos
			if (count($dadosJSON["idiomas"])>0) {
				foreach ($dadosJSON['idiomas'] as $idioma => $val) {
					$dadosFoto["resultados"]["titulo_".$idioma]=$dadosJSON['idiomas'][$idioma]["titulo"];
					$dadosFoto["resultados"]["visivel_".$idioma]=$dadosJSON['idiomas'][$idioma]["visivel"];
				}
				$dadosFoto["resultados"]["id"]=$dadosJSON['id'];
				//Faz o update no banco
				dbUpdate("fotos", $dadosFoto);
			}
			
			//Informações referentes ao Crop
			$dadosCrop["resultados"]["altura"]=$dadosJSON['altuCrop'];
			$dadosCrop["resultados"]["largura"]=$dadosJSON['largCrop'];
			$dadosCrop["resultados"]["x"]=$dadosJSON['posicaoX'];
			$dadosCrop["resultados"]["y"]=$dadosJSON['posicaoY'];
			
			if ($dadosJSON['idCrop']!="0") {
				$dadosCrop["resultados"]["id"]=$dadosJSON['idCrop'];
				//Faz o update no banco
				dbUpdate("crops", $dadosCrop);
			} else {
				$dadosCrop["resultados"]["relacao_id"]=$dadosJSON['id'];
				$dadosCrop["resultados"]["modulo"]=$modulo;
				$idCrop=dbSave("crops", $dadosCrop,true);
			}

			//Retorno dos dados do JSON
			$retorno['status']   = '1';
			$retorno['mensagem'] = 'Alterações na imagem foram salvas.';
		} elseif( $dadosJSON['tipo'] == 'video' ){
			//Informações referentes aos dados da tabela de fotos
			if (count($dadosJSON["idiomas"])>0) {
				foreach ($dadosJSON['idiomas'] as $key=>$val) {
					$dadosVideo["resultados"]["titulo_".$key]=$dadosJSON['idiomas'][$key]["titulo"];
					$dadosVideo["resultados"]["visivel_".$key]=$dadosJSON['idiomas'][$key]["visivel"];
				}
			}
			$dadosVideo["resultados"]["id"]=$dadosJSON['id'];
			if ($dadosJSON['url_video']!="") {
				$dadosVideo["resultados"]["arquivo"]=$dadosJSON['url_video'];
			}
			//Faz o update no banco
			dbUpdate("videos", $dadosVideo);
			
			//Retorno dos dados do JSON
			$retorno['status']   = '1';
			$retorno['mensagem'] = 'Alterações no vídeo foram salvas.';
		} elseif( $dadosJSON['tipo'] == 'anexo' ){
			//Informações referentes aos dados da tabela de fotos
			if (count($dadosJSON["idiomas"])>0) {
				foreach ($dadosJSON['idiomas'] as $key=>$val) {
					$dadosAnexo["resultados"]["titulo_".$key]=$dadosJSON['idiomas'][$key]["titulo"];
					$dadosAnexo["resultados"]["visivel_".$key]=$dadosJSON['idiomas'][$key]["visivel"];
				}
			}
			$dadosAnexo["resultados"]["id"]=$dadosJSON['id'];
			//Faz o update no banco
			dbUpdate("anexos", $dadosAnexo);
			
			//Retorno dos dados do JSON
			$retorno['status']   = '1';
			$retorno['mensagem'] = 'Alterações no anexo foram salvas.';
		}
		echo newJSON($retorno);
	}
	function cadastro_url($dadosJSON) {
		//Pega o ID do módulo
		$modelSite=new ACKsite_Model();
		$modulo=$modelSite->idModulo($dadosJSON["modulo"]);

		//Pega a próxima ordem de arquivo, colocando ele sempre no final
		$ordem = proximaOrdem("videos", array("modulo"=>$modulo,"relacao_id"=>$dadosJSON["id"],"status"=>"1"));

		//Gera o array final com os dados para salvar no banco		
		$dadosVideo["resultados"]=array("arquivo"=>$dadosJSON["url"],"modulo"=>$modulo,"relacao_id"=>$dadosJSON["id"],"ordem"=>$ordem,"tipo"=>$dadosJSON["tipoURL"],"status"=>"1");

		// Passa todos os idiomas enviados pelo JSON para preencher o campo de visível e o título do arquivo
		foreach ($dadosJSON["idiomas"] as $idioma => $visivel) {
			$dadosVideo["resultados"]["titulo_".$idioma]="Vídeo";
			$dadosVideo["resultados"]["visivel_".$idioma]=$visivel;
		}

		//Salva no banco
		dbSave("videos", $dadosVideo);
		
		$retorno['status']   = '1';
		$retorno['mensagem'] = 'Videos enviados com sucesso.';	
		echo newJSON($retorno);
	}
}
?>