<?php
class ACKmodulos_Controller
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
		$moduloVerifica=$modelSite->idModulo("modulos",true);

		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true);	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));

		//Pega os dado dos tópicos
		$modelGeral=new ACKgeral_Model();
		$dados["tituloPagina"]="Seções do site";
		loadView("ack/modulos",$dados);
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
		$visitasSite=$modelSite->visitasSite();
		$dados["conteudoIdiomas"]=$modelSite->conteudoIdioma("modulos",$id,"descricao");
		$dados["dadosSite"]=$dadosSite;

		//Carrega o ID do Módulo
		$moduloVerifica=$modelSite->idModulo("modulos",true);
		
		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true,"1");	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));
		
		//Nível de permissão das Metatags
		$moduloVerificaMetatags=$modelSite->idModulo("metatags_ack",true);
		$dados["nivelMetatags"]=$modelUser->userLevel($moduloVerificaMetatags,false, false);
		
		//Pega os dados do Destaque
		$modelGeral=new ACKgeral_Model();
		$dados["dadosModulo"]=$modelGeral->dataModulo(array("id"=>$id,"ack"=>"0"));
		if (!$dados["dadosModulo"]) {
			$dadosErro["erro"]=array("titulo"=>"SEÇÃO NÃO EXISTE","conteudo"=>"A seção que você tentou acessar não existe ou foi excluída.","linkACK"=>true);
			loadView("__erro",$dadosErro);
			exit;
		}
		
		//Pega os dados das Metatags
		$dados["metatags"]=$modelSite->metaTagsSite("modulos",$id);
		$dados["tipoAcao"]="editar";
		$dados["tituloPagina"]="Editar Seção do Site";
	
		//Libera o acesso para edição do conteúdo, ou apenas visualização
		$nivelPermissao=$modelUser->userLevel($moduloVerifica,false);
		$dados["nivelPermissao"]=$nivelPermissao["nivel"];
		
		loadView("ack/modulo",$dados);
	}
	function salvar() {
		postRequest();
		$dadosJSON=readJSON($_POST["ajaxACK"]);
		protectedArea();
		openSession();
		verifyTimeSession();
		
		//Carrega o ID do Módulo
		$modelSite=new ACKsite_Model();
		$moduloVerifica=$modelSite->idModulo("modulos",true);
		
		$modelUser=new ACKuser_Model();
		if (!$modelUser->userLevel($moduloVerifica,false,"2")) {
			$json['status']="0";
			$json['mensagem']="Usuário sem permissão para salvar seção. ";
		} else {
			// Salva o tópico
			$dados["resultados"]=$dadosJSON["modulos"];
			dbUpdate("modulos", $dados);
				
			//Gera o retorno do JSON
			$json['status'] = 1;
			$json['mensagem']= "Seção salva com sucesso! ";			
		}
		
		//Carrega o ID do Módulo
		$moduloMetatag=$modelSite->idModulo("metatags_ack",true);

		$modelUser=new ACKuser_Model();
		if($modelUser->userLevel($moduloMetatag,false,"2")) {
			// Pega o ID da Metatag
			$modelSite=new ACKsite_Model();
			$idMT=$modelSite->metaTagsSite("modulos",$dadosJSON["modulos"]["id"]);
			if (!$idMT) {
				$dadosMetaTag["resultados"]["tabela"]="modulos";
				$dadosMetaTag["resultados"]["item"]=$dadosJSON["modulos"]["id"];
				$idMetatag=dbSave("metatags",$dadosMetaTag,true);
			} else {
				$idMetatag=$idMT["id"];
			}
			
			// Salva a Metatag
			if ($dadosJSON["metaTags"]["title"] or $dadosJSON["meTatags"]["description"] or $dadosJSON["metaTags"]["keywords"]) {
				$dadosMetaTag["resultados"]=$dadosJSON["metaTags"];
				$dadosMetaTag["resultados"]["id"]=$idMetatag;
				dbUpdate("metatags", $dadosMetaTag);
			}
			
			//Gera o retorno do JSON
			$json['status'] = 1;
			$json['mensagem'].= "Metatags salvas com sucesso! ";
		}
		echo newJSON($json);
	}
}
?>