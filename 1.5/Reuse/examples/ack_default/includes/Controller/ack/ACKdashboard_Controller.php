<?php
class ACKdashboard_Controller
{
    function index () {
		protectedArea();
		openSession();
		verifyTimeSession();
		$dados=array();
		//Pega os dados do Site
		$modelSite=new ACKsite_Model();
		$dadosSite=$modelSite->dadosSite();
		$visitasSite=$modelSite->visitasSite();
		$dados["dadosSite"]=$dadosSite;
		$dados["visitasSite"]=$visitasSite;
		
		//Pega os dados dos Logs
		$modelLogs=new ACKlogs_Model();
		$dadosLogs=$modelLogs->lastUpdate();
		$dados["dadosLogs"]=$dadosLogs;

		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		//$permissaoUser=$modelUser->userLevel("dashboard");
		$dadosUser=$modelUser->dataUser(array("email"=>$_SESSION["email"]));
		$dados["dadosUser"]=$dadosUser;
		$dados["dadosUserHeader"]=$dadosUser;
		$dados["tituloPagina"]="Dashboard";
		loadView("ack/dashboard",$dados);
	}
}
?>