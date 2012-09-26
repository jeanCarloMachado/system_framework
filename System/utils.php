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
		echo '<pre>';
		if(is_array($data))
			print_r($data);
		else
			echo $data;
		echo '</pre>';
		die;
	}

	/**
	 * mostra um array e continua a execuçao
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	function sw($data) 
	{	
		echo '<pre>';
		if(is_array($data))
			print_r($data);
		else
			echo $data;
		echo '</pre>';
	}
	
	/**
	 * converte um objeto para array
	 * @param  [type] $object [description]
	 * @return [type]         [description]
	 */
	function objectToArray($object) 
	{
		if (is_object($object)) 
		{
			$object = get_object_vars($object);
		}

		if (is_array($object)) {
			return array_map(__FUNCTION__, $object);
		} else {
			return $object;
		}
	}

	/**
	 * função que le um arquivo JSON
	 * @param  [type]  $json        [description]
	 * @param  boolean $converteURL [description]
	 * @return [type]               [description]
	 */
	function readJSON ($json,$converteURL=false) 
	{
		if ($converteURL) {
			 $json=urldecode($json);
		}
		if (get_magic_quotes_gpc()) {
			$json=stripslashes($json);
			return json_decode($json, true);
		} else {
			return json_decode($json, true);
		}
	}

	function newJSON ($data) {
		return json_encode($data);
	}

// 	/**
// 	 * verifica se a página está sendo chamada via post
// 	 * @return [type] [description]
// 	 */
// 	function postRequest()
// 	{
// 		if ($_SERVER['REQUEST_METHOD']=="POST") {
// 			return true;	
// 		}

// 		return false;
// 	}


// 	/**
// 	 * funções copiadas do ack
// 	 */

// 	// Função que cria um Cookie
// 	function newCookie ($nomeCookie, $valor, $duracao=false, $diretorio=false, $ack=true) {
// 	if (!$duracao) {
// 	$duracao=time()+31556926;
// 	} else {
// 	$duracao=time()+$duracao;
// 	}
// 	if (!$diretorio) {
// 	global $diretorio_cookie;
// 	$diretorio=$diretorio_cookie;
// 	}
// 	if ($ack) {
// 	$diretorio="ack/".$diretorio;
// 	}
// 	global $servidor_cookie;
// 	setcookie($nomeCookie, $valor, $duracao, $diretorio, $servidor_cookie);
// 	}
// 	// Função que lê um Cookie e retorna o valor dele
// 	function readCookie ($nomeCookie) {
// 	return $_COOKIE[$nomeCookie];
// 	}
// 	// Função que destroy um Cookie
// 	function delCookie ($nomeCookie, $diretorio=false,$ack=true) {
// 	if (!$diretorio) {
// 	global $diretorio_cookie;
// 	$diretorio=$diretorio_cookie;
// 	}
// 	if ($ack) {
// 	$diretorio="ack/".$diretorio;
// 	}
// 	global $servidor_cookie;
// 	setcookie ($nomeCookie, "", time() - 3600, $diretorio, $servidor_cookie);
// 	unset ($_COOKIE[$nomeCookie]);
// 	}


// // Função que adiciona dias em uma data. O formato da data deve ser date("Ymd");  O formato de saída é a data em padrão americano, separado por hifens
// function addDays($date,$days) {
// $thisyear = substr ( $date, 0, 4 );
// $thismonth = substr ( $date, 4, 2 );
// $thisday =  substr ( $date, 6, 2 );
// $nextdate = mktime ( 0, 0, 0, $thismonth, $thisday + $days, $thisyear );
// return strftime("%Y-%m-%d", $nextdate);
// }
// // Função que subtrai dias em uma data. O formato da data deve ser date("Ymd"); O formato de saída é a data em padrão americano, separado por hifens
// function subDays($date,$days) {
// $thisyear = substr ( $date, 0, 4 );
// $thismonth = substr ( $date, 4, 2 );
// $thisday =  substr ( $date, 6, 2 );
// $nextdate = mktime ( 0, 0, 0, $thismonth, $thisday - $days, $thisyear );
// return strftime("%Y-%m-%d", $nextdate);
// }

// //Função que retorna a extensão do arquivo
// function extensao ($arquivo) {
// 	$tam = strlen($arquivo);
// 	//ext de 3 chars
// 	if( $arquivo[($tam)-4] == '.' )	{
// 		$extensao = substr($arquivo,-3);
// 	}
	
// 	//ext de 4 chars
// 	elseif( $arquivo[($tam)-5] == '.' )	{
// 		$extensao = substr($arquivo,-4);
// 	}
	
// 	//ext de 2 chars
// 	elseif( $arquivo[($tam)-3] == '.' )	{
// 		$extensao = substr($arquivo,-2);
// 	}
	
// 	//Caso a extensão não tenha 2, 3 ou 4 chars ele não aceita e retorna Nulo.
// 	else {
// 		$extensao = NULL;
// 	}
// 	return $extensao;
// }


// //Função que retorna o tamanho do arquivo
// function tamanhoArquivo($arquivo) {
// 	global $endereco_fisico;
// 	$arquivo=$endereco_fisico.$arquivo;
// 	if (file_exists($arquivo)) {
// 		$tamanhoarquivo = filesize($arquivo);
// 		$bytes = array('KB', 'MB', 'GB', 'TB');
	
// 		if($tamanhoarquivo <= 999) {
// 			$tamanhoarquivo = 1;
// 		}
		
// 		for($i=-1; $tamanhoarquivo > 999; $i++) {
// 			$tamanhoarquivo = $tamanhoarquivo / 1024;
// 		}
		
// 		return round($tamanhoarquivo,2).$bytes[$i];
// 	} else {
// 		return "0KB";
// 	}
// }
// //Função que retorna o tamanho da imagem em pixels
// function tamanhoImagem($arquivo) {
//     $tam_img = getimagesize($arquivo);
// 	return array ("largura"=>$tam_img[0], "altura"=>$tam_img[1]);
// }
// //Função que retorna o Thumb do Youtube
// function youtubeThumb($url,$tamanho="p") {
// 	if ($tamanho=="p") {
// 		$tamanhoThumb="default";
// 	} elseif ($tamanho=="m") {
// 		$tamanhoThumb="hqdefault";
// 	} elseif ($tamanho=="g") {
// 		$tamanhoThumb="maxresdefault";
// 	}
// 	if(strlen($url)) {
// 		$pattern='#(\.be/|/embed/|/v/|/watch\?v=)([A-Za-z0-9_-]{5,11})#';
// 		preg_match($pattern, $url, $matches);
// 		$thumb="http://img.youtube.com/vi/".$matches[2]."/".$tamanhoThumb.".jpg";
// 		return $thumb;
// 	}
// }
// //Função que retorna o Thumb do Vimeo
// function vimeoThumb($url,$tamanho="m") {
// 	if ($tamanho=="p") {
// 		$tamanhoThumb="small";
// 	} elseif ($tamanho=="m") {
// 		$tamanhoThumb="medium";
// 	} elseif ($tamanho=="g") {
// 		$tamanhoThumb="large";
// 	}
// 	if(strlen($url)) {
// 		$pattern="#(\.com/)([0-9]{5,11})#";
// 		preg_match($pattern, $url, $matches);
// 		$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$matches[2].".php"));
// 		$thumb=$hash[0]["thumbnail_".$tamanhoThumb];
// 		return $thumb;
// 	}
// }
// //Função que retorna o nome do Setor
// function retornaSetor($idSetor,$todos=false) {
// 	//Pega os idiomas do Site
// 	$modelSite=new site_Model();
// 	$idioma=$modelSite->idiomasSite("1");
	
// 	if (!$todos) {	
// 		//Pega o nome do setor
// 		global $db;
// 		//$mysql = $db->prepare('SELECT * FROM setores_contato WHERE id="'.$idSetor.'";');
// 		$mysql = $db->prepare('SELECT * FROM setores WHERE id="'.$idSetor.'";');
// 		$mysql->execute();
// 		$resultados = $mysql->fetchAll();
// 		if (count($resultados)>0) {
// 			return $resultados[0]["titulo_".$idioma[0]["abreviatura"]];
// 		} else {
// 			return false;	
// 		}
// 	} else {
// 		//Retorna todos os setores
// 		global $db;
// 		$mysql = $db->prepare('SELECT * FROM setores_contato WHERE status<>"9";');
// 		$mysql->execute();
// 		$resultados = $mysql->fetchAll();
// 		if (count($resultados)>0) {
// 			return $resultados;
// 		} else {
// 			return false;	
// 		}
// 	}
// }
// //Função que limita o número de caracteres de uma string
// function limitaCaracteres($texto,$maxchar,$end='') {
// 	$text=strip_tags($texto);
// 	if (strlen($text)>$maxchar) {
// 		$words=explode(" ",$text);
// 		$output = '';
// 		$i=0;
// 		while(1){
// 			$length = (strlen($output)+strlen($words[$i]));
// 			if($length>$maxchar){
// 				break;
// 			}else{
// 				$output = $output." ".$words[$i];
// 				++$i;
// 			};
// 		};
// 		return $output.$end;
// 	} else {
// 		return strip_tags($texto);
// 	}
// }
	
	
?>
