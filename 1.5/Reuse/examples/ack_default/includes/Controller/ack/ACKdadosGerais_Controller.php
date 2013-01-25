<?php
class ACKdadosGerais_Controller
{
    function index () {
		protectedArea();
		openSession();
		verifyTimeSession();
		$dados=array();
		//Pega os dados do Site
		$modelSite=new ACKsite_Model();
		$dados["dadosSite"]=$modelSite->dadosSite();
		$dados["idiomasSite"]=$modelSite->idiomasSite();
		$dados["metaTagsSite"]=$modelSite->metaTagsSite("sistema","1");
		$dados["enderecoSite"]=$modelSite->enderecoSite();
		
		//Carrega o ID do Módulo
		$moduloVerifica=$modelSite->idModulo("geral_ack",true);
		$moduloVerificaMetatags=$modelSite->idModulo("metatags_ack",true);

		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true);
		$dados["nivelMetatags"]=$modelUser->userLevel($moduloVerificaMetatags,false, false);
		$dados["nivelSistema"]=$modelUser->userLevel($moduloVerifica,false, false);
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));
		$dados["tituloPagina"]="Dados Gerais";
		loadView("ack/dados_gerais",$dados);
	}
	function salvar() {
		postRequest();
		protectedArea();
		openSession();
		verifyTimeSession();
	
		//Carrega o ID do Módulo
		$modelSite=new ACKsite_Model();
		$moduloVerifica=$modelSite->idModulo("geral_ack",true);
		
		//Verifica se o usuário tem permissão para salvar
		$modelUser=new ACKuser_Model();
		if (!$modelUser->userLevel($moduloVerifica,false,"2")) {
			$json['status'] = "0";
			$json['mensagem'] = "Usuário sem permissão";
		} else {
			$dadosJSON=readJSON($_POST["ajaxACK"]);
			foreach ($dadosJSON as $key=>$val) {
				if ($key!="acao") {
					$dados["resultados"]=$dadosJSON[$key];
					dbUpdate($key, $dados);
				}
			}
			if ($dadosJSON["idioma"]) {
				$json["idioma"]=$dadosJSON["idioma"];
			} else {
				$json['status'] = "1";
				$json['mensagem']= "Dados salvos com sucesso! ";
			}
		}
		echo newJSON($json);
	}
}
?>