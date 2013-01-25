<?php
class ACKinstitucional_Controller
{
    function index () {
		protectedArea();
		openSession();
		verifyTimeSession();
		$dados=array();
		//Pega os dados do Site
		$modelSite=new ACKsite_Model();
		$dadosSite=$modelSite->dadosSite();
		$dados["dadosSite"]=$dadosSite;
		(int)$limite=$dadosSite["itens_pagina"];
		$dados["idioma"]=$modelSite->idiomasSite("1");

		//Carrega o ID do Módulo
		$moduloVerifica=$modelSite->idModulo("institucional",false);

		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true);	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));
		$dados["dragDrop"]=true;

		//Pega os dado dos tópicos
		$modelGeral=new ACKgeral_Model();
		$dados["topicos"]=$modelGeral->listaTopicos("0",$limite);
		$dados["tituloPagina"]="Institucional";
		loadView("ack/institucional",$dados);
	}
	function excluir($dadosJSON) {
		postRequest();
		$dadosJSON=readJSON($_POST["ajaxACK"]);
		protectedArea();
		openSession();
		verifyTimeSession();
		$itens_lista=explode(",",$dadosJSON["itens_lista"]);
		
		//Carrega o ID do Módulo
		$modelSite=new ACKsite_Model();
		$moduloVerifica=$modelSite->idModulo("institucional",false);
		
		$modelUser=new ACKuser_Model();
		if (!$modelUser->userLevel($moduloVerifica, false, "2")) {
			$json['status']=0;
			$json["mensagem"]="Usuário sem permissão";
		} else {
			$total=0;
			$json["array"]=array();
			foreach ($itens_lista as $item) {
				dbDelete("institucional", $item);
				array_push($json["array"], $item);
				$total++;
			}
			$json['status']=1;
			$json["total"]=$total;
		}
		echo newJSON($json);
	}
    function incluir () {
		protectedArea();
		openSession();
		verifyTimeSession();
		$dados=array();
		//Pega os dados do Site
		$modelSite=new ACKsite_Model();
		$dadosSite=$modelSite->dadosSite();
		$dados["dadosSite"]=$dadosSite;
		$dados["plugins"]=true;
		$dados["multiplasImagens"]=true; //True se puder enviar várias imagens, false se puder enviar apenas uma
		$dados["tamanhoCrop"]="500 400"; //String com a largura (espaço) altura do crop, ou false se não houver crop
		$dados["abaImagens"]=true; //True se aba imagens deve estar visível ou false se não deve
		$dados["abaVideos"]=true; //True se aba imagens deve estar visível ou false se não deve
		$dados["abaAnexos"]=true; //True se aba imagens deve estar visível ou false se não deve

		$dados["conteudoIdiomas"]=$modelSite->idiomasSite();

		//Carrega o ID do Módulo
		$moduloVerifica=$modelSite->idModulo("institucional",false);

		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true,"2");	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));

		//Nível de permissão das Metatags
		$moduloVerificaMetatags=$modelSite->idModulo("metatags_ack",true);
		$dados["nivelMetatags"]=$modelUser->userLevel($moduloVerificaMetatags,false, false);
		$dados["nivelPermissao"]="2";		

		//Carrega a View
		$dados["tipoAcao"]="incluir";
		$dados["tituloPagina"]="Adicionar Tópico";
		loadView("ack/institucional_topico",$dados);
	}
    function editar ($dados) {
		$id=$dados[0];
		protectedArea();
		openSession();
		verifyTimeSession();
		$dados=array();
		
		//Pega os dados do Site
		$modelSite=new ACKsite_Model();
		$dadosSite=$modelSite->dadosSite();
		$dados["conteudoIdiomas"]=$modelSite->conteudoIdioma("institucional",$id,"titulo");
		$dados["dadosSite"]=$dadosSite;
		
		//Carrega o ID do Módulo
		$moduloVerifica=$modelSite->idModulo("institucional",false);

		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true,"1");	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));
		
		//Pega os dados do Tópico
		$modelGeral=new ACKgeral_Model();
		$dados["dadosTopico"]=$modelGeral->dataTopico(array("id"=>$id));
		if (!$dados["dadosTopico"]) {
			$dadosErro["erro"]=array("titulo"=>"TÓPICO NÃO EXISTE","conteudo"=>"O tópico que você tentou acessar não existe ou foi excluído.","linkACK"=>true);
			loadView("__erro",$dadosErro);
			exit;
		}
		
		//Pega os dados das Metatags
		$dados["metatags"]=$modelSite->metaTagsSite("institucional",$id);
		$dados["plugins"]=true;
		$dados["multiplasImagens"]=true; //True se puder enviar várias imagens, false se puder enviar apenas uma
		$dados["tamanhoCrop"]="500 400"; //String com a largura (espaço) altura do crop, ou false se não houver crop
		$dados["abaImagens"]=true; //True se aba imagens deve estar visível ou false se não deve
		$dados["abaVideos"]=true; //True se aba imagens deve estar visível ou false se não deve
		$dados["abaAnexos"]=true; //True se aba imagens deve estar visível ou false se não deve

		$dados["tipoAcao"]="editar";
		$dados["tituloPagina"]="Editar Tópico";
		
		//Libera o acesso para edição do conteúdo, ou apenas visualização
		$nivelPermissao=$modelUser->userLevel($moduloVerifica,false);
		$dados["nivelPermissao"]=$nivelPermissao["nivel"];

		//Nível de permissão das Metatags
		$moduloVerificaMetatags=$modelSite->idModulo("metatags_ack",true);
		$dados["nivelMetatags"]=$modelUser->userLevel($moduloVerificaMetatags,false, false);
		
		loadView("ack/institucional_topico",$dados);
	}
	function salvar() {
		postRequest();
		$dadosJSON=readJSON($_POST["ajaxACK"]);
		protectedArea();
		openSession();
		verifyTimeSession();

		//Carrega o ID do Módulo
		$modelSite=new ACKsite_Model();
		$moduloVerifica=$modelSite->idModulo("institucional",false);
		
		$modelUser=new ACKuser_Model();
		if (!$modelUser->userLevel($moduloVerifica,false,"2")) {
			$json['status'] = 0;
			$json['mensagem'] = "Usuário sem permissão para salvar tópico. ";
		} else {
			if ($dadosJSON["acao"]=="incluir") {
				//Salva o Tópico
				$dados["resultados"]=$dadosJSON["institucional"];
				$dados["resultados"]["visivel"]=$dadosJSON["visivel"];
				$dados["resultados"]["status"]="1";
				$ordem = proximaOrdem("institucional", array("status"=>"1"));
				$dados["resultados"]["ordem"]=$ordem;
				$idItem=dbSave("institucional",$dados,true);
				
				//Cria a Meta-tag
				$dadosMetaTag["resultados"]["tabela"]="institucional";
				$dadosMetaTag["resultados"]["item"]=$idItem;
				dbSave("metatags",$dadosMetaTag);
				
				//Gera os retornos do JSON
				$json['status'] = 1;
				$json['id'] = $idItem;
				$json['mensagem'] = "Tópico incluído com sucesso. ";
			} elseif ($dadosJSON["acao"]=="editar") {
				// Salva o tópico
				$dados["resultados"]=$dadosJSON["institucional"];
				$dados["resultados"]["visivel"]=$dadosJSON["visivel"];
				dbUpdate("institucional", $dados);
					
				//Gera os retornos do JSON
				$json['status'] = 1;
				$json['mensagem'] = "Dados salvos com sucesso no tópico. ";

				//Carrega o ID do Módulo
				$moduloMetatag=$modelSite->idModulo("metatags_ack",true);

				$modelUser=new ACKuser_Model();
				if($modelUser->userLevel($moduloMetatag,false,"2")) {
					// Pega o ID da Metatag
					$modelSite=new ACKsite_Model();
					$idMT=$modelSite->metaTagsSite("institucional",$dadosJSON["institucional"]["id"]);
					if (!$idMT) {
						$dadosMetaTag["resultados"]["tabela"]="institucional";
						$dadosMetaTag["resultados"]["item"]=$dadosJSON["institucional"]["id"];
						$idMetatag=dbSave("metatags",$dadosMetaTag,true);
					} else {
						$idMetatag=$idMT["id"];
					}
					
					// Salva a Metatag
					if ($dadosJSON["metatags"]["title"] or $dadosJSON["metatags"]["description"] or $dadosJSON["metatags"]["keywords"]) {
						$dadosMetaTag["resultados"]=$dadosJSON["metatags"];
						$dadosMetaTag["resultados"]["id"]=$idMetatag;
						dbUpdate("metatags", $dadosMetaTag);
					}
					
					//Gera o retorno do JSON
					$json['status'] = 1;
					$json['mensagem'].= "Metatags salvas com sucesso! ";
				}
			}
		}
		echo newJSON($json);
	}
}
?>