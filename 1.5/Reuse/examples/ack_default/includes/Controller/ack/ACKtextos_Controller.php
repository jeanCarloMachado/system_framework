<?php
class ACKtextos_Controller
{
    function index () {
		protectedArea();
		openSession();
		verifyTimeSession();
		$dados=array();
		//Pega os dados do Site
		$modelSite=new ACKsite_Model();
		$dados["dadosSite"]=$modelSite->dadosSite();
		
		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));

		//Pega os dados dos Textos
		$modelTextos=new ACKtextos_Model();
		$dados["dadosTextos"]=$modelTextos->listaTextos("h");
		$dados["tituloPagina"]="Ajuda";
		loadView("ack/ajuda",$dados);
	}
    function privacidade () {
		protectedArea();
		openSession();
		verifyTimeSession();
		$dados=array();
		//Pega os dados do Site
		$modelSite=new ACKsite_Model();
		$dados["dadosSite"]=$modelSite->dadosSite();
		
		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));

		//Pega os dados dos Textos
		$modelTextos=new ACKtextos_Model();
		$dados["dadosTextos"]=$modelTextos->carregaTexto("33",true);
		$dados["tituloPagina"]="Política de Privacidade";
		loadView("ack/privacidade",$dados);
	}
    function termos () {
		protectedArea();
		openSession();
		verifyTimeSession();
		$dados=array();
		//Pega os dados do Site
		$modelSite=new ACKsite_Model();
		$dados["dadosSite"]=$modelSite->dadosSite();
		
		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));

		//Pega os dados dos Textos
		$modelTextos=new ACKtextos_Model();
		$dados["dadosTextos"]=$modelTextos->carregaTexto("146",true);
		$dados["tituloPagina"]="Termos de Uso";
		loadView("ack/termos",$dados);
	}
}
?>