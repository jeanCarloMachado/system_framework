<?php
class ACKdestaques_Controller
{
    function index () {
		protectedArea();
		openSession();
		verifyTimeSession();
		$dados=array();
		//Pega os dados do Site
		$modelSite=new ACKsite_Model();
		$dadosSite=$modelSite->dadosSite();
		(int)$limite=$dadosSite["itens_pagina"];
		$dados["dadosSite"]=$dadosSite;
		$dados["idioma"]=$modelSite->idiomasSite("1");
		
		//Carrega o ID do Módulo
		$moduloVerifica=$modelSite->idModulo("destaques",true);

		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true);	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));
		$dados["dragDrop"]=true;
		
		//Pega os dados dos Destaques da Home
		$modelGeral=new ACKgeral_Model();
		$dados["tituloPagina"]="Imagens de destaque";
		loadView("ack/destaques",$dados);
	}
	function editar($dados) {
		$id=$dados[0];
		protectedArea();
		openSession();
		verifyTimeSession();
		$dados=array();
		//Pega os dados do Site
		$modelSite=new ACKsite_Model();
		$dadosSite=$modelSite->dadosSite();
		$visitasSite=$modelSite->visitasSite();
		$dados["conteudoIdiomas"]=$modelSite->conteudoIdioma("destaques",$id,"titulo");
		$dados["dadosSite"]=$dadosSite;
		$dados["modulos"]=$modelSite->modulosDestaque();

		//Carrega o ID do Módulo
		$moduloVerifica=$modelSite->idModulo("destaques",true);
		
		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true,"1");	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));
		
		//Pega os dados do Destaque
		$modelGeral=new ACKgeral_Model();
		$dados["dadosDestaque"]=$modelGeral->dataDestaque(array("id"=>$id));
		if (!$dados["dadosDestaque"]) {
			$dadosErro["erro"]=array("titulo"=>"DESTAQUE NÃO EXISTE","conteudo"=>"O destaque que você tentou acessar não existe ou foi excluído.","linkACK"=>true);
			loadView("__erro",$dadosErro);
			exit;
		}
		$dados["plugins"]=true;
		$dados["multiplasImagens"]=false; //True se puder enviar várias imagens, false se puder enviar apenas uma
		$dados["tamanhoCrop"]="500 300"; //String com a largura (espaço) altura do crop, ou false se não houver crop
		$dados["abaImagens"]=true; //True se aba imagens deve estar visível ou false se não deve
		$dados["abaVideos"]=false; //True se aba imagens deve estar visível ou false se não deve
		$dados["abaAnexos"]=false; //True se aba imagens deve estar visível ou false se não deve
		
		$dados["tipoAcao"]="editar";
		$dados["tituloPagina"]="Editar Destaque";
		
		//Libera o acesso para edição do conteúdo, ou apenas visualização
		$nivelPermissao=$modelUser->userLevel($moduloVerifica,false);
		$dados["nivelPermissao"]=$nivelPermissao["nivel"];
		
		loadView("ack/destaque",$dados);
	}
	function incluir() {
		protectedArea();
		openSession();
		verifyTimeSession();
		$dados=array();
		//Pega os dados do Site
		$modelSite=new ACKsite_Model();
		$dadosSite=$modelSite->dadosSite();
		$dados["conteudoIdiomas"]=$modelSite->idiomasSite();
		$dados["dadosSite"]=$dadosSite;
		$dados["modulos"]=$modelSite->modulosDestaque();

		//Carrega o ID do Módulo
		$moduloVerifica=$modelSite->idModulo("destaques",true);

		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true,"2");	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));
		$dados["tipoAcao"]="incluir";
		$dados["plugins"]=true;
		$dados["multiplasImagens"]=false; //True se puder enviar várias imagens, false se puder enviar apenas uma
		$dados["tamanhoCrop"]="500 300"; //String com a largura (espaço) altura do crop, ou false se não houver crop
		$dados["abaImagens"]=true; //True se aba imagens deve estar visível ou false se não deve
		$dados["abaVideos"]=false; //True se aba imagens deve estar visível ou false se não deve
		$dados["abaAnexos"]=false; //True se aba imagens deve estar visível ou false se não deve
		
		$dados["tituloPagina"]="Adicionar Destaque";
		
		//Libera o acesso para edição do conteúdo, ou apenas visualização
		$dados["nivelPermissao"]="2";
		
		loadView("ack/destaque",$dados);
	}
	function salvar() {
		postRequest();
		$dadosJSON=readJSON($_POST["ajaxACK"]);
		protectedArea();
		openSession();
		verifyTimeSession();

		//Carrega o ID do Módulo
		$modelSite=new ACKsite_Model();		
		$moduloVerifica=$modelSite->idModulo("destaques",true);
		
		$modelUser=new ACKuser_Model();
		if ($modelUser->userLevel($moduloVerifica,false,"2")) {
			if ($dadosJSON["acao"]=="incluir") {
				$dados["resultados"]=$dadosJSON["destaques"];
				$dados["resultados"]["visivel"]=$dadosJSON["visivel"];
				$dados["resultados"]["status"]="1";
				$ordem = proximaOrdem("destaques", array("modulo"=>"0", "status"=>"1"));
				$dados["resultados"]["ordem"]=$ordem;
				$destaqueID=dbSave("destaques",$dados,true);
				$json['status'] = 1;
				$json['mensagem']= "Dados salvos com sucesso! ";
				$json['id'] = $destaqueID;
			} elseif ($dadosJSON["acao"]=="editar") {
				$dados["resultados"]=$dadosJSON["destaques"];
				$dados["resultados"]["visivel"]=$dadosJSON["visivel"];
				dbUpdate("destaques", $dados);
				$json['status'] = 1;
				$json['mensagem']= "Dados salvos com sucesso! ";
			}
		} else {
			$json['status'] = 0;
			$json['mensagem'] = "Usuário sem permissão.";
		}
		echo newJSON($json);
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
		$moduloVerifica=$modelSite->idModulo("destaques",true);

		$modelUser=new ACKuser_Model();
		if (!$modelUser->userLevel($moduloVerifica,false,"2")) {
			$json['status']="0";
			$json['mensagem']="Usuário sem permissão";
		} else {
			$total=0;
			$json["array"]=array();
			foreach ($itens_lista as $item) {
				dbDelete("destaques", $item);
				array_push($json["array"], $item);
				$total++;
			}
			$json['status']=1;
			$json["total"]=$total;
		}
		echo newJSON($json);
	}
}
?>