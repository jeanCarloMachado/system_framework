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
				print_r(($data));
			else
				print_r((array($data)));
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
	
	function create_zip($files = array(),$destination = '',$overwrite = false) {
		//if the zip file already exists and overwrite is false, return false
		if(file_exists($destination) && !$overwrite) { return false; }
		//vars
		$valid_files = array();
		//if files were passed in...
		if(is_array($files)) {
			//cycle through each file
			foreach($files as $file) {
				//make sure the file exists
				if(file_exists($file)) {
					$valid_files[] = $file;
				}
			}
		}
		//if we have good files...
		if(count($valid_files)) {
			//create the archive
			$zip = new ZipArchive();
			if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
				return false;
			}
			//add the files
			foreach($valid_files as $file) {
				$zip->addFile($file,$file);
			}
			//debug
			//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
	
			//close the zip -- done!
			$zip->close();
	
			//check to make sure the file exists
			return file_exists($destination);
		}
		else
		{
			return false;
		}
	}

	function directoryToArray($directory, $recursive) {
		$array_items = array();
		if ($handle = opendir($directory)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					if (is_dir($directory. "/" . $file)) {
						if($recursive) {
							$array_items = array_merge($array_items, directoryToArray($directory. "/" . $file, $recursive));
						}
						$file = $directory . "/" . $file;
						$array_items[] = preg_replace("/\/\//si", "/", $file);
					} else {
						$file = $directory . "/" . $file;
						$array_items[] = preg_replace("/\/\//si", "/", $file);
					}
				}
			}
			closedir($handle);
		}
		return $array_items;
	}
	
	
	
	
?>
