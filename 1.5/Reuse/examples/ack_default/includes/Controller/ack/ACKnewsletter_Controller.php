<?php
class ACKnewsletter_Controller
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
		$moduloVerifica=$modelSite->idModulo("newsletter",false);

		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true);	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));

		//Pega os dado dos tópicos
		$dados["tituloPagina"]="Newsletter";
		loadView("ack/newsletter",$dados);
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
		$moduloVerifica=$modelSite->idModulo("newsletter",false);	
		
		$modelUser=new ACKuser_Model();
		if (!$modelUser->userLevel($moduloVerifica, false, "2")) {
			$json['status']=0;
			$json["mensagem"]="Usuário sem permissão";
		} else {
			$total=0;
			$json["array"]=array();
			foreach ($itens_lista as $item) {
				dbDelete("newsletter", $item);
				array_push($json["array"], $item);
				$total++;
			}
			$json['status']=1;
			$json["total"]=$total;
		}
		echo newJSON($json);
	}
	function exportar() {
		protectedArea();
		openSession();
		verifyTimeSession();
		$dadosJSON=readJSON($_POST["ajaxACK"],true);
		//Pega os dados do Site
		$modelSite=new ACKsite_Model();
		$dadosSite=$modelSite->dadosSite();

		//Carrega o ID do Módulo
		$moduloVerifica=$modelSite->idModulo("newsletter",false);

		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true);

		//Chama o Model Geral para listar os cadastros
		$modelGeral=new ACKgeral_Model();
		$newsletters=$modelGeral->listaNewsletter(0,99999999,false,$dadosJSON["data"]);
		
		header("Content-type: text/csv, charset=UTF-8; encoding=UTF-8'");  
		header("Cache-Control: no-store, no-cache");  
		header('Content-Disposition: attachment; filename="newsletter.csv"');  

		//Imprime o cabeçalho
		echo "Nome,Email,Sexo\n";
		
		//Imprime os registros
		foreach ($newsletters as $newsletter) {
			//Sexo
			if ($newsletter["sexo"]=="m") {
				$sexo="Masculino";
			} elseif ($newsletter["sexo"]=="f") {
				$sexo="Feminino";
			}
			echo $newsletter["nome"].",".$newsletter["email"].",".$sexo."\n";
		}			
	}
}
?>