<?php
class ACKcontatos_Controller
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
		$moduloVerifica=$modelSite->idModulo("contatos",true);

		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true);	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));

		//Pega os dado dos tópicos
		$modelGeral=new ACKgeral_Model();
		$dados["tituloPagina"]="Contatos";
		loadView("ack/contatos",$dados);
	}
    function visualizar($dados) {
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
		$moduloVerifica=$modelSite->idModulo("contatos",true);
		
		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true,"1");	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));
		
		//Pega os dados do Destaque
		$modelGeral=new ACKgeral_Model();
		$dados["dadosContato"]=$modelGeral->dataContato(array("id"=>$id));
		if (!$dados["dadosContato"]) {
			$dadosErro["erro"]=array("titulo"=>"CONTATO NÃO EXISTE","conteudo"=>"O contato que você tentou acessar não existe ou foi excluído.","linkACK"=>true);
			loadView("__erro",$dadosErro);
			exit;
		}

		//Marca a mensagem como lida
		$dadosContato["resultados"]["id"]=$id;
		$dadosContato["resultados"]["lido"]="1";
		dbUpdate("contatos", $dadosContato);
		
		//Forma os dados da View
		$dados["tituloPagina"]="Visulizar contato";
		loadView("ack/contato",$dados);
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
		$moduloVerifica=$modelSite->idModulo("contatos",true);
		
		$modelUser=new ACKuser_Model();
		if (!$modelUser->userLevel($moduloVerifica,false,"2")) {
			$json['status'] = 0;
			$json['mensagem'] = "Usuário sem permissão.";
		} else {
			$total=0;
			$json["array"]=array();
			foreach ($itens_lista as $item) {
				dbDelete("contatos", $item);
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