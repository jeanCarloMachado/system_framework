<?php

require_once "config.php";
require_once "connect.php";
require_once "helpers.php";
require_once "languages.php";


class application {

	public $url;
	public $method;
	public $controller;
	public $controllerName;

	function __construct ($urlRecebida) {
		global $debug;
		//Ativar o debug do sistema
		if ($debug==0) {
			ini_set('display_errors', '0');
			error_reporting(0);
		} elseif ($debug==1) {
			ini_set('display_errors', '1');
			error_reporting(E_ALL ^ E_NOTICE);
		} elseif ($debug==3) {
			ini_set('display_errors', '1');
			error_reporting(E_ALL);
		}

		//Faz um array com os parametros das variáveis. O index zero é o Controller
		$urlRecebida=explode("/", $urlRecebida);

		$url=array();
		foreach ($urlRecebida as $urlLimpa)  {
			array_push($url, protegeURL($urlLimpa));
		}

		//Lê a URL
		$controllerURL=$url[0];

		//Compara o controller, se for referente ao ACK cria variáveis específicas, caso contrário, mantém a ordem da URL
		if ($controllerURL=="ack") {
			$controllerURL=$url[1];
			if (empty($controllerURL)) {
				$controllerName="ACKhome_Controller";
			} else {
				$controllerName="ACK".$url[1]."_Controller";
			}
			$method=$url[2];
		} else {
			if (empty($controllerURL)) {
				$controllerName="home_Controller";
			} else {
				$controllerName=$url[0]."_Controller";
			}
			$method=$url[1];
		}

		
		//Função que faz o load do da classe que estiver sendo solicitada
		function __autoload($classe) {
			$ackDir = substr($classe, 0, 3);
			if ($ackDir=="ACK") {
				$ackDir="ack/";
			} else {
				$ackDir=false;
			}

			$tipo=explode("_",$classe);
			$tipo=$tipo[1]; // Pega o tipo da classe, se é um controller ou uma view.
		        
			try {
				if (!@include_once( "includes/".$tipo."/".$ackDir.$classe.".php" )) {
					throw new Exception ("O arquivo includes/".$tipo."/".$ackDir.$classe.".php não existe. A classe não foi carregada.");
				}
			}
			catch(Exception $e) {    
				$errno = "404";
				$erro=$e->getMessage();
				error_log($erro);
				$dadosErro["erro"]=array("titulo"=>"ERRO ".$errno,"conteudo"=>$erro,"linkACK"=>false);
				loadView("__erro",$dadosErro);
				exit;
			}
		}
		
		$ackDir = substr($controllerName, 0, 3);
		if ($ackDir=="ACK") {
			$ackDir=true;
		} else {
			$ackDir=false;
		}

		$modelSite = new ACKsite_Model();
		$dadosSite=$modelSite->dadosSite();

		if (!$ackDir) {
			if ($dadosSite["publicado"]=="0") {
				loadView("placeholder/index",$dadosSite);
				die;
			}
		}	

		global $cache;
		// Cache das páginas para poupar rendimento
		if ($cache) {
			header('Cache-Control: max-age=3600, public');
			header('Pragma: cache');
			header("Last-Modified: ".gmdate("D, d M Y H:i:s",time())." GMT");
			header("Expires: ".gmdate("D, d M Y H:i:s",time()+3600)." GMT");
		}
		header("Content-Type: text/html; charset=utf-8");
		$this->url=$url;
		$this->method=$method;
		$this->controllerName=$controllerName;
		global $db;
		$this->db=$db;
	}
	

	function bootstrap () {
		require_once "bootstrap.php";
		$bootstrap=new bootstrap;
		$bootstrap->index($this);
	}

	function index () {


		$this->controller = new $this->controllerName;	
		$url = $this->url;
		$method = $this->method;
		$controller = $this->controller;
		$controllerName = $this->controllerName;

		$ackDir = substr($controllerName, 0, 3);
		if ($ackDir=="ACK") {
			$ackDir=true;
		} else {
			$ackDir=false;
		}

		//Executa a index do controlador ou a sua função se for o caso da URL estar informando
		if ($method) {
			$parametros=$url;
			unset($parametros[0]);
			unset($parametros[1]);
			if ($ackDir) {
				unset($parametros[2]);
			}
			ksort($parametros);
			$parametros = array_values($parametros); // re-ordena o array para ficar com as keys em ordem, partindo do zero
			if (method_exists($controller,$method)) { // verifica se o método da classe existe, se não existe chama um erro 404
				$controller->$method($parametros);
			} else {
				$errno = "404";
				$erro = "Ops, não encotramos a página que você procurava.";
				$dadosErro["erro"]=array("titulo"=>"ERRO ".$errno,"conteudo"=>$erro,"linkACK"=>false);
				loadView("__erro",$dadosErro);
				exit;
			}
		} else {
			$controller->index();	
		}

	}

}
?>
