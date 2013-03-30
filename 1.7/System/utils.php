<?php
	/**
	 * arquivo com funções que ainda nao receberam uma classe
	 */

	/**
	 * mostra um array e morre
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	function dg($data) 
	{	
		sw($data);
		die;
	}

	/**
	 * mostra um array e continua a execuçao
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	function sw($data) 
	{	
		/**
		 * testa se é json
		 */
		echo '<pre>';
			if(is_array($data))
				print_r($data);
			elseif (is_object($data) || is_null($data))
				var_dump($data);
				//print_r(array($data));
			else
				echo $data;
		echo '</pre>';
			
		return;
	}
	
	function sstream_resolve_include_path($path) 
	{
		$prefixPaths = get_include_path();

		$separator = ":";
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') { 
			$separator = ";";
		}

		$prefixPaths = explode($separator, $prefixPaths);


		foreach($prefixPaths as $prefixPath) {
			if(file_exists($prefixPath."/".$path))
				return $path;   
		}

		return null;
	}

	function sreset(array $arr)
	{
		return reset($arr);
	}

	/**
	 * testa se a sessao foi startada
	 * @return [type] [description]
	 */
	function session_started() 
	{
		return (isset($_COOKIE["PHPSESSID"])) ? 1 : 0;
	}
	
	/**
	 * função que torna os erros amigáveis
	 * @param unknown $errno
	 * @param unknown $errstr
	 * @param unknown $errfile
	 * @param unknown $errline
	 * @return boolean
	 */
	function sys_magicError($errno, $errstr, $errfile, $errline) 
	{
		$erro=false;
		switch ($errno) {
			case E_USER_ERROR:
				$erro = "Erro fatal na linha ".$errline." do arquivo ".$errfile."<br />\n";
				$erro .= "Sua versão do PHP é " . PHP_VERSION . " (" . PHP_OS . ")\n";
				break;
	
			case E_USER_WARNING:
				$erro = "<b>ATENÇÃO</b> [".$errno."] ".$errstr."<br />\n";
				$erro .= "Linha ".$errline." do arquivo ".$errfile."\n";
				break;
	
			case E_USER_NOTICE:
				$erro = "<b>IMPORTANTE</b> [".$errno."] ".$errstr."<br />\n";
				$erro .= "Linha ".$errline." do arquivo ".$errfile."\n";
				break;
	
			case E_STRICT:
				$erro = "<b>ERRO</b> [".$errno."] ".$errstr."<br />\n";
				$erro .= "Linha ".$errline." do arquivo ".$errfile."\n";
				break;
		}
		if ($erro) {
			error_log($erro);
			$dadosErro["erro"]=array("titulo"=>"ERRO ".$errno,"conteudo"=>$erro,"linkACK"=>false);
			loadView("__erro",$dadosErro);
		}
		return true;
	}
	
	
	//função que trata os try's and catch's
	function sys_catchError($erro) 
	{
		error_log($erro);
		$dadosErro["erro"]=array("titulo"=>"ERRO FATAL","conteudo"=>"Ocorreu um erro fatal: ".$erro,"linkACK"=>false);
		loadView("__erro",$dadosErro);
		exit;
	}
	
	function loadView($view,$variaveis = array()) {
		global $endereco_site;
		global $endereco_fisico;
		global $lang;
		// Cria variáveis para serem usadas na View baseado nos parametros enviados para a função
		extract($variaveis);
	
		// Se vários layouts forem enviados, lê o array previamente enviado e varre eles e faz os src
		if (is_array($view)) {
			foreach ($view as $include) {
				try {
					if (!@include_once(include_once ("src/View/".$include.".php"))) {
						throw new Exception ("O arquivo src/View/".$include.".php não existe. A view não foi carregada.");
					}
				}
				catch(Exception $e) {
					$errno="404";
					$erro=$e->getMessage();
					error_log($erro);
					$dadosErro["erro"]=array("titulo"=>"ERRO ".$errno,"conteudo"=>$erro,"linkACK"=>false);
					loadView("__erro",$dadosErro);
					exit;
				}
			}
		} else {
			try {
				if (!@include_once("src/View/".$view.".php")) {
					throw new Exception ("O arquivo src/View/".$view.".php não existe. A view não foi carregada.");
				}
			}
			catch(Exception $e) {
				$errno="404";
				$erro=$e->getMessage();
				error_log($erro);
				$dadosErro["erro"]=array("titulo"=>"ERRO ".$errno,"conteudo"=>$erro,"linkACK"=>false);
				loadView("__erro",$dadosErro);
				exit;
			}
		}
	}

	
	
	
	
?>
