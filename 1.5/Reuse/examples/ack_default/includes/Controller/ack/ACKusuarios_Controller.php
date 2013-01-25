<?php
class ACKusuarios_Controller
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

		//Carrega o ID do Módulo
		$moduloVerifica=$modelSite->idModulo("usuarios_ack",true);
		
		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true);	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));
		$dados["dadosUsuarios"]=$modelUser->listaUsuarios("0",$limite);
		$dados["tituloPagina"]="Usuários";
		loadView("ack/usuarios",$dados);
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
		$dados["dadosSite"]=$dadosSite;
		
		//Carrega o ID do Módulo
		$moduloVerifica=$modelSite->idModulo("usuarios_ack",true);
		
		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$permissao_acesso=$modelUser->userLevel($moduloVerifica,false,"2");	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));
		$dados["dadosUser"]=$modelUser->dataUser(array("id"=>$id));
		if (!$dados["dadosUser"]) {
			$dadosErro["erro"]=array("titulo"=>"USUÁRIO NÃO EXISTE","conteudo"=>"O usuário que você tentou acessar não existe ou foi excluído.","linkACK"=>true);
			loadView("__erro",$dadosErro);
			exit;
		}
		$dados["tipoAcao"]="editar";
		if (!$permissao_acesso) {
			$dados["nivelAcesso"]="1";
		} else {
			$dados["nivelAcesso"]="2";
		}
		$dados["tituloPagina"]="Editar Usuário";
		loadView("ack/usuario",$dados);
	}
	function meusDados() {
		protectedArea();
		openSession();
		verifyTimeSession();
		$dados=array();
		//Pega os dados do Site
		$modelSite=new ACKsite_Model();
		$dadosSite=$modelSite->dadosSite();
		$visitasSite=$modelSite->visitasSite();
		$dados["dadosSite"]=$dadosSite;
		
		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));
		$dados["dadosUser"]=$dados["dadosUserHeader"];
		$dados["tipoAcao"]="editar";
		$dados["meusDados"]=true;
		$dados["tituloPagina"]="Meus Dados";
		loadView("ack/usuario",$dados);
	}
	function incluir() {
		protectedArea();
		openSession();
		verifyTimeSession();
		
		//Carrega o ID do Módulo
		$modelSite=new ACKsite_Model();
		$moduloVerifica=$modelSite->idModulo("usuarios_ack",true);
		
		$modelUser=new ACKuser_Model();
		$permissao_acesso=$modelUser->userLevel($moduloVerifica,true,"2");
		$dados=array();
		//Pega os dados do Site
		$dadosSite=$modelSite->dadosSite();
		$visitasSite=$modelSite->visitasSite();
		$dados["dadosSite"]=$dadosSite;

		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));
		$dados["tipoAcao"]="incluir";
		$dados["tituloPagina"]="Adicionar Usuário";
		loadView("ack/usuario",$dados);
	}
	function salvar() {
		postRequest();
		$dadosJson=readJSON($_POST["ajaxACK"]);
		protectedArea();
		openSession();
		verifyTimeSession();

		//Carrega o ID do Módulo
		$modelSite=new ACKsite_Model();
		$moduloVerifica=$modelSite->idModulo("usuarios_ack",true);
		
		$modelUser=new ACKuser_Model();
		$permissao_acesso=$modelUser->userLevel($moduloVerifica, false);

		if ($dadosJson["acao"]=="incluir") {
			if ($permissao_acesso["nivel"]=="2") {
				$dadosVerifica["resultados"]=array("email"=>$dadosJson["email"]);
				dbDuplicated("usuarios",$dadosVerifica, "json");
				$dadosUser["resultados"]=array("nome"=>$dadosJson["nome"],"nome_tratamento"=>$dadosJson["nome_tratamento"],"email"=>$dadosJson["email"],"senha"=>md5($dadosJson["senha"]),"primeira_senha"=>"1","status"=>"1");
				$userID=dbSave("usuarios",$dadosUser,true);
				$modulos=$modelUser->listaModulos();
				foreach($modulos as $modulo) {
					$dadosPerm["resultados"]=array("usuario"=>$userID,"nivel"=>"0","modulo"=>$modulo["id"]);
					dbSave("permissoes",$dadosPerm, false, false); 
				}
				$dadosEmail=$dadosJson;
				$dadosEmail["assunto"]="Criação de Usuário";
				if (!enviaEmail($dadosJson["email"], "ACKnew_user", $dadosEmail)) {
					$json['mensagem'] = "Usuário cadastrado com sucesso, mas o e-mail de confirmação não foi enviado";
					$json['status'] = 1;
					$json['id'] = $userID;
				} else {
					$json['status'] = 1;
					$json['id'] = $userID;
				}
				echo newJSON($json);
			} else {
				$json['status'] = 0;
				$json['mensagem'] = "Usuário sem permissão.";
				echo newJSON($json);
			}
		} elseif ($dadosJson["acao"]=="editar") {
			$dadosVerifica["resultados"]=array("email"=>$dadosJson["email"],"id"=>$dadosJson["id"]);
			dbDuplicated("usuarios",$dadosVerifica, "json");
			if ($dadosJson["id"]==$_SESSION["id"]) {
				if ($dadosJson["senha"]!="") {
					$dadosUser["resultados"]=array("id"=>$dadosJson["id"],"nome"=>$dadosJson["nome"],"nome_tratamento"=>$dadosJson["nome_tratamento"],"email"=>$dadosJson["email"],"acessoACK"=>$dadosJson["acessoACK"],"senha"=>md5($dadosJson["senha"]),"primeira_senha"=>"0");
				} else {
					$dadosUser["resultados"]=array("id"=>$dadosJson["id"],"nome"=>$dadosJson["nome"],"nome_tratamento"=>$dadosJson["nome_tratamento"],"email"=>$dadosJson["email"],"acessoACK"=>$dadosJson["acessoACK"]);
				}
				updateSession ("email",$dadosJson["email"]);
				updateSession ("nome",$dadosJson["nome"]);
			} else {
				if ($permissao_acesso["nivel"]=="2") {
					if ($dadosJson["senha"]!="") {
						$dadosUser["resultados"]=array("id"=>$dadosJson["id"],"nome"=>$dadosJson["nome"],"nome_tratamento"=>$dadosJson["nome_tratamento"],"email"=>$dadosJson["email"],"acessoACK"=>$dadosJson["acessoACK"],"senha"=>md5($dadosJson["senha"]),"primeira_senha"=>"0");
					} else {
						$dadosUser["resultados"]=array("id"=>$dadosJson["id"],"nome"=>$dadosJson["nome"],"nome_tratamento"=>$dadosJson["nome_tratamento"],"email"=>$dadosJson["email"],"acessoACK"=>$dadosJson["acessoACK"]);
					}
				} else {
					$json['status'] = 0;
					$json['mensagem'] = "Usuário sem permissão.";
					echo newJSON($json);
					exit;
				}
			}
			dbUpdate("usuarios", $dadosUser);
			if ($permissao_acesso["nivel"]=="2") {
				foreach ($dadosJson["permissoes"] as $key=>$val) {
					dbUpdate("permissoes", array("resultados"=>array("id"=>$key, "nivel"=>$val)));
				}
			}
			$json['status'] = 1;
			$json['mensagem'] = "Usuário salvo com sucesso.";
			echo newJSON($json);
		}
	}
	function save_permissoes($dadosJSON) {
		postRequest();
		$dadosJSON=readJSON($_POST["ajaxACK"]);
		protectedArea();
		openSession();
		verifyTimeSession();

		//Carrega o ID do Módulo
		$modelSite=new ACKsite_Model();
		$moduloVerifica=$modelSite->idModulo("usuarios_ack",true);
		
		$modelUser=new ACKuser_Model();
		$permissao_acesso=$modelUser->userLevel($moduloVerifica, false, "2");
		if (!$permissao_acesso) {
			$json=array('status'=>0, 'mensagem'=>'Usuário sem Permissão');
			echo newJSON($json);
			exit;
		}
		
		foreach ($dadosJSON["permissao"] as $key=>$val) {
			dbUpdate("permissoes", array("resultados"=>array("id"=>$key, "nivel"=>$val)));
		}
		$json['status'] = 1;
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
		$moduloVerifica=$modelSite->idModulo("usuarios_ack",true);
		
		$modelUser=new ACKuser_Model();
		if (!$modelUser->userLevel($moduloVerifica,false,"2")) {
			$json['status'] = 0;
			$json['mensagem'] = "Usuário sem permissão.";
		} else {
			$total=0;
			$json["array"]=array();
			foreach ($itens_lista as $item) {
				dbDelete("usuarios", $item);
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