<?php
class ACKsetores_Controller
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
		$moduloVerifica=$modelSite->idModulo("setores",true);

		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true);	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));

		//Pega os dado dos tópicos
		$dados["tituloPagina"]="Setores de Contato";
		$dados["dragDrop"]=true;
		loadView("ack/setores",$dados);
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
		$moduloVerifica=$modelSite->idModulo("setores",true);
		
		$modelUser=new ACKuser_Model();
		if (!$modelUser->userLevel($moduloVerifica, false, "2")) {
			$json['status']=0;
			$json["mensagem"]="Usuário sem permissão";
		} else {
			$total=0;
			$json["array"]=array();
			foreach ($itens_lista as $item) {
				dbDelete("setores", $item);
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
		$dados["conteudoIdiomas"]=$modelSite->idiomasSite();

		//Carrega o ID do Módulo
		$moduloVerifica=$modelSite->idModulo("setores",true);

		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true,"2");	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));
		$dados["nivelPermissao"]="2";		

		//Carrega a View
		$dados["tipoAcao"]="incluir";
		$dados["tituloPagina"]="Adicionar Setor";
		loadView("ack/setor",$dados);
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
		$dados["dadosSite"]=$dadosSite;
		$dados["conteudoIdiomas"]=$modelSite->conteudoIdioma("setores",$id,"titulo");

		//Carrega o ID do Módulo
		$moduloVerifica=$modelSite->idModulo("setores",true);
		
		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true,"1");	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));
		
		//Pega os dados do Destaque
		$modelGeral=new ACKgeral_Model();
		$dados["dadosSetor"]=$modelGeral->dataSetor(array("id"=>$id));
		if (!$dados["dadosSetor"]) {
			$dadosErro["erro"]=array("titulo"=>"SETOR NÃO EXISTE","conteudo"=>"O setor que você tentou acessar não existe ou foi excluída.","linkACK"=>true);
			loadView("__erro",$dadosErro);
			exit;
		}

		//Libera o acesso para edição do conteúdo, ou apenas visualização
		$nivelPermissao=$modelUser->userLevel($moduloVerifica,false);
		$dados["nivelPermissao"]=$nivelPermissao["nivel"];

		//Nível de permissão das Metatags
		$moduloVerificaMetatags=$modelSite->idModulo("metatags_ack",true);
		$dados["nivelMetatags"]=$modelUser->userLevel($moduloVerificaMetatags,false, false);
		
		//Pega os dados das Metatags
		$dados["tipoAcao"]="editar";
		$dados["tituloPagina"]="Editar Setor";
		loadView("ack/setor",$dados);
	}
	function salvar() {
		postRequest();
		$dadosJSON=readJSON($_POST["ajaxACK"]);
		protectedArea();
		openSession();
		verifyTimeSession();
		
		//Carrega o ID do Módulo
		$modelSite=new ACKsite_Model();
		$moduloVerifica=$modelSite->idModulo("setores",true);
		
		$modelUser=new ACKuser_Model();
		if (!$modelUser->userLevel($moduloVerifica,false,"2")) {
			$json['status'] = 0;
			$json['mensagem'] = "Usuário sem permissão. ";
		} else {
			if ($dadosJSON["acao"]=="incluir") {
				//Salva o Tópico
				$dados["resultados"]=$dadosJSON["setores"];
				$dados["resultados"]["visivel"]=$dadosJSON["visivel"];
				$dados["resultados"]["status"]="1";
				$idItem=dbSave("setores",$dados,true);
							
				//Gera os retornos do JSON
				$json['status'] = 1;
				$json['id'] = $idItem;
			} elseif ($dadosJSON["acao"]=="editar") {
				// Salva o tópico
				$dados["resultados"]=$dadosJSON["setores"];
				$dados["resultados"]["visivel"]=$dadosJSON["visivel"];
				dbUpdate("setores", $dados);
				
				//Gera o retorno do JSON
				$json['status'] = 1;
				$json['mensagem'] = "Dados salvos com sucesso!";		
			}
		}
		echo newJSON($json);
	}
}
?>