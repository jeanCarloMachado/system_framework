<?php
class ACKenderecos_Controller
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

		//Carrega o ID do Módulo
		$moduloVerifica=$modelSite->idModulo("enderecos",true);

		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true);	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));
		$dados["dragDrop"]=true;

		//Pega os dado dos tópicos
		$dados["tituloPagina"]="Endereços";
		loadView("ack/enderecos",$dados);
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
		$moduloVerifica=$modelSite->idModulo("enderecos",true);
		
		$modelUser=new ACKuser_Model();
		if (!$permissao_acesso=$modelUser->userLevel($moduloVerifica, false, "2")) {
			$json['status']=0;
			$json["total"]="Usuário sem permissão";
		} else {
			$total=0;
			$json["array"]=array();
			foreach ($itens_lista as $item) {
				dbDelete("enderecos", $item);
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
		$moduloVerifica=$modelSite->idModulo("enderecos",true);

		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true,"2");	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));

		//Carrega a View
		$dados["dadosSite"]=false;
		$dados["tipoAcao"]="incluir";
		$dados["tituloPagina"]="Adicionar Endereço";
		$dados["nivelPermissao"]="2";
		loadView("ack/endereco",$dados);
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
		$dados["conteudoIdiomas"]=$modelSite->conteudoIdioma("enderecos",$id,"nome");

		//Carrega o ID do Módulo
		$moduloVerifica=$modelSite->idModulo("enderecos",true);
		
		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true,"1");	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));
		
		//Pega os dados do Endereço
		$dados["enderecoSite"]=$modelSite->dataEndereco(array("id"=>$id, "status"=>"1"));
		if (empty($dados["enderecoSite"])) {
			$dadosErro["erro"]=array("titulo"=>"ENDEREÇO NÃO EXISTE","conteudo"=>"O endereço que você tentou acessar não existe ou foi excluído.","linkACK"=>true);
			loadView("__erro",$dadosErro);
			exit;
		}
		
		//Pega os dados das Metatags
		$dados["tipoAcao"]="editar";
		$dados["tituloPagina"]="Editar Endereço";
		
		//Libera o acesso para edição do conteúdo, ou apenas visualização
		$nivelPermissao=$modelUser->userLevel($moduloVerifica,false);
		$dados["nivelPermissao"]=$nivelPermissao["nivel"];
		
		loadView("ack/endereco",$dados);
	}
	function salvar() {
		postRequest();
		$dadosJSON=readJSON($_POST["ajaxACK"]);
		protectedArea();
		openSession();
		verifyTimeSession();

		//Carrega o ID do Módulo
		$modelSite=new ACKsite_Model();
		$moduloVerifica=$modelSite->idModulo("enderecos",true);
		
		$modelUser=new ACKuser_Model();
		if(!$modelUser->userLevel($moduloVerifica,false,"2")) {
			$json['status'] = 0;
			$json['mensagem'] = "Usuário sem permissão.";
		} else {
			if ($dadosJSON["acao"]=="incluir") {
				//Salva o Tópico
				$dados["resultados"]=$dadosJSON["enderecos"];
				$dados["resultados"]["visivel"]=$dadosJSON["visivel"];
				$dados["resultados"]["status"]="1";
				$ordem = proximaOrdem("enderecos", array("status"=>"1"));
				$dados["resultados"]["ordem"]=$ordem;
				$idItem=dbSave("enderecos",$dados,true);
			
				//Gera os retornos do JSON
				$json['status'] = 1;
				$json['mensagem']= "Dados salvos com sucesso! ";
				$json['id'] = $idItem;
			} elseif ($dadosJSON["acao"]=="editar") {
				// Salva o tópico
				$dados["resultados"]=$dadosJSON["enderecos"];
				$dados["resultados"]["visivel"]=$dadosJSON["visivel"];
				dbUpdate("enderecos", $dados);
				
				//Gera o retorno do JSON
				$json['status'] = 1;
				$json['mensagem']= "Dados salvos com sucesso! ";
			}
		}
		echo newJSON($json);
	}
}
?>