<?php
class ACKlogs_Controller
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
		$moduloVerifica=$modelSite->idModulo("logs",true);
		
		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true);	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));
		$dados["dadosUsuarios"]=$modelUser->listaUsuarios("0");

		//Pega os dados dos Logs
		$modelLogs=new ACKlogs_Model();
		$dados["dadosLogs"]=$modelLogs->listaLogs("0",$limite);
		$dados["tituloPagina"]="Logs";
		loadView("ack/logs",$dados);
	}
}
?>