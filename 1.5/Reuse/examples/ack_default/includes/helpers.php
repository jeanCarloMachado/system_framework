<?php
/*================================================================================================*/
// Este arquivo é automaticamente chamado pelo ACK.
// Toda vez que quiser adicionar uma nova função comum, inclua ela aqui e lembre-se de comentar
// Funções que auxiliam o ACK. Aqui estarão funções comuns para qualquer Controller ou Model
/*================================================================================================*/

// Esta função prevê erros na função que será chamada e se houver problema já mostra uma mensagem amigável dela
function magicError($errno, $errstr, $errfile, $errline) {
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

//Define que os erros comuns vão ser tratados pela função abaixo
set_error_handler("magicError");

//Função que pega o erro do try/catch e deixa ele amigável
function catchError($erro) {
	error_log($erro);
	$dadosErro["erro"]=array("titulo"=>"ERRO FATAL","conteudo"=>"Ocorreu um erro fatal: ".$erro,"linkACK"=>false);
	loadView("__erro",$dadosErro);
	exit;
}
//Define que os erros do método try/catch serão tratados pela função abaixo
set_exception_handler('catchError');

// Esta função carrega a View e adiciona as variáveis que irão compor o layout
function loadView($view,$variaveis = array()) {
	global $endereco_site;
	global $endereco_fisico;
	global $lang;
	// Cria variáveis para serem usadas na View baseado nos parametros enviados para a função
	extract($variaveis);
	
	// Se vários layouts forem enviados, lê o array previamente enviado e varre eles e faz os includes
	if (is_array($view)) {
		foreach ($view as $include) {
			try {
				if (!@include_once(include_once ("includes/View/".$include.".php"))) {
					throw new Exception ("O arquivo includes/View/".$include.".php não existe. A view não foi carregada.");
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
			if (!@include_once("includes/View/".$view.".php")) {
				throw new Exception ("O arquivo includes/View/".$view.".php não existe. A view não foi carregada.");
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

// Função que elimina da URL alguns caracteres proibidos que estão definidos no arquivo config.php
function protegeURL($str) {
	$termo=mb_strtolower($str, 'UTF-8');
	global $caracteresInvalidos;
	global $caracteresConvertidos;
	$invalidos=array();
	foreach ($caracteresInvalidos as $cInv) {
		array_push($invalidos, utf8_encode($cInv));
	}
	return str_replace($invalidos, $caracteresConvertidos, $str);
}

// Função que elimina da URL acentos e demais caracteres estranhos
function limpaURL($str){
    $str = strtolower(utf8_decode($str)); $i=1;
    $str = strtr($str, utf8_decode('àáâãäåæçèéêëìíîïñòóôõöøùúûýýÿ'), 'aaaaaaaceeeeiiiinoooooouuuyyy');
    $str = preg_replace("/([^a-z0-9])/",'-',utf8_encode($str));
    while($i>0) $str = str_replace('--','-',$str,$i);
    if (substr($str, -1) == '-') $str = substr($str, 0, -1);
    return $str;
}
// Função que busca próxima ordem do item, serializado ou não
function ordemAtual($tabela, $arrayValores, $serializado=false, $idItem) {
	$arrayColunas = array_keys($arrayValores); // Pega as chaves do array
		
	if ($serializado) {
		// Executa o comando no banco de dados
		global $db;
		$sql='SELECT * FROM '.$tabela.' WHERE id="'.$idItem.'" LIMIT 1;';
		$mysql = $db->prepare($sql);
		$mysql->execute();
		$resultados = $mysql->fetchAll();
		foreach ($resultados as $resultado) {
			if ($resultado["categorias"]=="") {
				$ordemAtual=proximaOrdem($tabela, $arrayValores, true);
			} else {
				$serial = unserialize($resultado["categorias"]);
				foreach ($serial as $categoria => $ordem) {
					if ($categoria==$arrayValores["categorias"]) {
						$ordemAtual=$ordem;
					} else {
						$ordemAtual=proximaOrdem($tabela, $arrayValores, true);
					}
				}
			}
		}
		return $ordemAtual;
	} else {		
		// Separa as chaves do array e coloca elas separadas por dois pontos e vírgula para definir a variável PDO dos valores
		$valores=array();
		foreach ($arrayColunas as $valColunas) {
			array_push($valores, $valColunas."=:".$valColunas);
		}
		$valores=implode(" AND ", $valores);

		// Executa o comando no banco de dados
		global $db;
		$sql='SELECT * FROM '.$tabela.' WHERE '.$valores.' ORDER BY ordem DESC LIMIT 1';
		$mysql = $db->prepare($sql);
		$mysql->execute($arrayValores);
		$retorno = $mysql->fetchAll();
		return $retorno[0]["ordem"];
	}
}
// Função que busca próxima ordem do item, serializado ou não
function proximaOrdem($tabela, $arrayValores, $serializado=false) {
	$arrayColunas = array_keys($arrayValores); // Pega as chaves do array
	
	if ($serializado) {
		$totalOrdem=array();
		// Executa o comando no banco de dados
		global $db;
		$sql='SELECT * FROM '.$tabela.' WHERE status="1";';
		$mysql = $db->prepare($sql);
		$mysql->execute();
		$resultados = $mysql->fetchAll();
		foreach ($resultados as $resultado) {
			$serial = unserialize($resultado["categorias"]);
			foreach ($serial as $categoria => $ordem) {
				if ($categoria==$arrayValores["categorias"]) {
					array_push($totalOrdem, (int)$ordem);
				}
			}
		}
		if (!empty($totalOrdem) and count($totalOrdem)>0) {
			rsort($totalOrdem);
			return $totalOrdem[0]+1;
		} else {
			return 1;
		}
	} else {
		// Separa as chaves do array e coloca elas separadas por dois pontos e vírgula para definir a variável PDO dos valores
		$valores=array();
		foreach ($arrayColunas as $valColunas) {
			array_push($valores, $valColunas."=:".$valColunas);
		}
		$valores=implode(" AND ", $valores);
		
		// Executa o comando no banco de dados
		global $db;
		$sql='SELECT * FROM '.$tabela.' WHERE '.$valores.' ORDER BY ordem DESC LIMIT 1';
		$mysql = $db->prepare($sql);
		$mysql->execute($arrayValores);
		$retorno = $mysql->fetchAll();
		return $retorno[0]["ordem"]+1;
	}
}
// Função que verifica se a página está sendo acessada via POST request
function postRequest(){
	if ($_SERVER['REQUEST_METHOD']!="POST") {
		$errno="500";
		$erro="Você não tem permissão para acessar esta página com este tipo de requisição";
		$dadosErro["erro"]=array("titulo"=>"ERRO ".$errno,"conteudo"=>$erro,"linkACK"=>false);
		loadView("__erro",$dadosErro);
		exit;
	}
}
// Função que elimina da URL alguns caracteres proibidos que estão definidos no arquivo config.php
function limpaPost() {
	foreach ($_POST as $key => $value) {
		$valorLimpo=str_replace(array("'", '"'), array("&lsquo;", '&quot;'), $value);
		$resultados[$key]=$valorLimpo;
	}
	return $resultados;
}
// Função para salvar dados no banco Mysql
function dbSave($tabela, $arrayValores,$retornaID=false, $saveLog=true) {
	$arrayColunas = array_keys($arrayValores["resultados"]); // Pega as chaves do array
	$colunas=implode(",", $arrayColunas); // Separa as chaves do array e coloca elas separadas com vírgula para definir as colunas
	
	// Separa as chaves do array e coloca elas separadas por dois pontos e vírgula para definir a variável PDO dos valores
	$valores=array();
	foreach ($arrayColunas as $valColunas) {
		array_push($valores, ":".$valColunas);
	}
	$valores=implode(",", $valores);

	// Executa o comando no banco de dados
	global $db;
	$sql='INSERT INTO '.$tabela.' ('.$colunas.') VALUES ('.$valores.')';
	$mysql = $db->prepare($sql);
	$mysql->execute($arrayValores["resultados"]);
	$id_inserido=$db->lastInsertId();
	
	if ($tabela!="logs" and $saveLog) {
		//Inclui o log no banco
		$logController=new ACKlogs_Model();
		$logController->salvar("incluiu", $tabela, $id_inserido, $sql);
	}

	if ($retornaID) {
	 	return $id_inserido;	
	}
}
// Função para editar dados no banco Mysql
function dbUpdate($tabela, $arrayValores, $saveLog=true) {
	$arrayColunas = array_keys($arrayValores["resultados"]); // Pega as chaves do array
	
	// Separa as chaves do array e coloca elas separadas por dois pontos e vírgula para definir a variável PDO dos valores
	$valores=array();
	foreach ($arrayColunas as $valColunas) {
		if ($valColunas!="id") {
			array_push($valores, $valColunas."=:".$valColunas);
		}
	}
	$valores=implode(", ", $valores);

	// Executa o comando no banco de dados
	global $db;
	$sql='UPDATE '.$tabela.' SET '.$valores.' WHERE id=:id';
	$mysql = $db->prepare($sql);
	$mysql->execute($arrayValores["resultados"]);

	if ($tabela!="logs" and $saveLog) {
		//Inclui o log no banco
		$logController=new ACKlogs_Model();
		$logController->salvar("editou", $tabela, $arrayValores["resultados"]["id"], $sql);
	}
}
// Função para excluir dados no banco Mysql
function dbDelete($tabela, $id, $saveLog=true, $logica=true, $where=false) {
	// Executa o comando no banco de dados
	global $db;
	if ($logica) {
		if (!$id and $where) {
			$sql='UPDATE '.$tabela.' SET status="9" WHERE '.$where.';';
			$mysql = $db->prepare($sql);
			$mysql->execute();
		} else {
			$sql='UPDATE '.$tabela.' SET status="9" WHERE id=:id';
			$mysql = $db->prepare($sql);
			$mysql->execute(array("id"=>$id));
		}
	} else {
		if (!$id and $where) {
			$sql='DELETE FROM '.$tabela.' WHERE '.$where.';';
			$mysql = $db->prepare($sql);
			$mysql->execute();
		} else {
			$sql='DELETE FROM '.$tabela.' WHERE id=:id';
			$mysql = $db->prepare($sql);
			$mysql->execute(array("id"=>$id));
		}
	}

	if ($tabela!="logs" and $saveLog) {
		//Inclui o log no banco
		$logController=new ACKlogs_Model();
		$logController->salvar("excluiu", $tabela, $id, $sql);
	}
}
// Função para verificar valores duplicados no Banco de Dados
function dbDuplicated($tabela, $arrayValores, $retorno, $msg="Item duplicado. A ação não foi executada.") {
	$arrayColunas = array_keys($arrayValores["resultados"]); // Pega as chaves do array
	
	// Separa as chaves do array e coloca elas separadas por dois pontos e vírgula para definir a variável PDO dos valores
	$valores=array();
	foreach ($arrayColunas as $valColunas) {
		if ($valColunas!="id") {
			array_push($valores, $valColunas."=:".$valColunas);
		} else {
			array_push($valores, $valColunas."<>:".$valColunas);
		}
	}
	$valores=implode(" AND ", $valores);

	// Executa o comando no banco de dados
	global $db;
	$mysql = $db->prepare('SELECT * FROM '.$tabela.' WHERE '.$valores.' AND status<>"9";');
	$mysql->execute($arrayValores["resultados"]);
	$total = $mysql->fetchAll();
	if (count($total)>=1) {
		if ($retorno=="json") {
			$json['status'] = 0;
			$json['mensagem'] = $msg;
			echo newJSON($json);
			exit;
		} elseif ($retorno=="html") {
			echo $msg;
			exit;
		} elseif ($retorno=="verifica") {
			return true;
		} else {
			$dadosErro["erro"]=array("titulo"=>"ITEM DUPLICADO","conteudo"=>$msg,"linkACK"=>true);
			loadView("__erro",$dadosErro);
			exit;
		}
	} else {
		return false;
	}
}
// Função que cria uma sessão com o padrão definido no config.php, ou o nome definido na função, além das variaveis de $_SESSION
function newSession ($sessao=false, $arrayVariaveis, $ack=true) {
	if (!$sessao) {
		global $nome_sessao;
		$sessao=$nome_sessao;
	}
	if ($ack) {
		$sessao=$sessao."ACK";	
	}
	session_name($sessao);
	session_start();
	//Varr as variaveis definindo as sessions
	foreach ($arrayVariaveis["resultados"] as $key => $value) {
		$_SESSION[$key]=$value;
	}
	session_write_close();
}
// Função que abre uma sessão definida, ou a padrão definida no config.php se nenhuma for declarada
function openSession ($sessao=false,$ack=true) {
	if (!$sessao) {
		global $nome_sessao;
		$sessao=$nome_sessao;
	}
	if ($ack) {
		$sessao=$sessao."ACK";	
	}
	session_name($sessao);
	session_start();
	session_write_close();
}
// Função que re-declara o valor de uma SESSION já existente
function updateSession ($variavel,$novoValor,$sessao=false,$ack=true) {
	if (!$sessao) {
		global $nome_sessao;
		$sessao=$nome_sessao;
	}
	if ($ack) {
		$sessao=$sessao."ACK";	
	}
	session_name($sessao);
	session_start();
	$_SESSION[$variavel]=$novoValor;
	session_write_close();
}
// Função que re-declara o valor de uma SESSION já existente
function verifyTimeSession ($sessao,$ack=true) {
	global $tempo_sessao;
	if ($tempo_sessao) {
		if (!$sessao) {
			global $nome_sessao;
			$sessao=$nome_sessao;
		}
		if ($ack) {
			$sessao=$sessao."ACK";	
		}
		session_name($sessao);
		session_start();
		session_write_close();
		if ($_SESSION["expire"]<time()) {
			$dadosErro["erro"]=array("titulo"=>"SESSÃO EXPIROU","conteudo"=>"O tempo da sua sessão expirou, faça login novamente para utilizar o painel ACK.","linkACK"=>true);
			loadView("__erro",$dadosErro);
			closeSession();
			delCookie("rU");
			delCookie("rS");
			exit;
		} else {
			updateSession("expire",time()+$tempo_sessao);
		}
	}
}
// Função que destrói a sessão definida, ou a padrão definida no config.php se nenhuma for declarada
function closeSession ($sessao=false,$ack=true) {
	if (!$sessao) {
		global $nome_sessao;
		$sessao=$nome_sessao;
	}
	if ($ack) {
		$sessao=$sessao."ACK";	
	}
	session_name($sessao);
	session_start();
	session_destroy();
	unset($_SESSION);
	session_id(uniqid (rand()));
	session_start();
}
// Função que cria um JSON baseado no array enviado
function newJSON ($valores) {
	return json_encode($valores);
}
// Função que lê um JSON e cria um array associativo
function readJSON ($json,$converteURL=false) {
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
// Função que cria um Cookie
function newCookie ($nomeCookie, $valor, $duracao=false, $diretorio=false, $ack=true) {
	if (!$duracao) {
		$duracao=time()+31556926;
	} else {
		$duracao=time()+$duracao;
	}
	if (!$diretorio) {
		global $diretorio_cookie;
		$diretorio=$diretorio_cookie;
	}
	if ($ack) {
		$diretorio=$diretorio."/ack";
	}
	global $servidor_cookie;
	setcookie($nomeCookie, $valor, $duracao, $diretorio, $servidor_cookie);
}
// Função que lê um Cookie e retorna o valor dele
function readCookie ($nomeCookie, $ack=true) {
	return $_COOKIE[$nomeCookie];
}
// Função que destroy um Cookie
function delCookie ($nomeCookie, $diretorio=false,$ack=true) {
	if (!$diretorio) {
		global $diretorio_cookie;
		$diretorio=$diretorio_cookie;
	}
	if ($ack) {
		$diretorio=$diretorio."/ack";
	}
	global $servidor_cookie;
	setcookie ($nomeCookie, "", time() - 3600, $diretorio, $servidor_cookie);
	unset ($_COOKIE[$nomeCookie]);
}
// Função que gera uma nova senha 
function geraSenha() {
	$vogais = 'aeiou';
	// A variável $consoante recebendo valor
	$consoante = 'bcdfghjklmnpqrstvwxyzbcdfghjklmnpqrstvwxyz';
	// A variável $numeros recebendo valor
	$numeros = '123456789';
	// A variável $simbolos recebendo valor
	$simbolos = '#.,_$@';
	// A variável $resultado vazia no momento
	$resultado = '';
	 
	// strlen conta o nº de caracteres da variável $vogais
	$a = strlen($vogais)-1;
	// strlen conta o nº de caracteres da variável $consoante
	$b = strlen($consoante)-1;
	// strlen conta o nº de caracteres da variável $numeros
	$c = strlen($numeros)-1;
	// strlen conta o nº de caracteres da variável $simbolos
	$d = strlen($simbolos)-1;
	 
	for($x=0;$x<=1;$x++) { // A função rand() tem objetivo de gerar um valor aleatório
		$aux1 = rand(0,$a);
		$aux2 = rand(0,$b);
		$aux3 = rand(0,$c);
		$aux4 = rand(0,$d);
		$aux5 = rand(0,$a);
		$aux6 = rand(0,$b);
		$aux7 = rand(0,$c);
		
		// A função substr() tem objetivo de retornar parte da string
		// Caso queira números com mais digitos mude de 1 para 2 e teste
		$str1 = substr($consoante,$aux1,1);
		$str2 = substr($vogais,$aux2,1);
		$str3 = substr($numeros,$aux3,1);
		$str4 = substr($simbolos,$aux4,1);
		$str5 = substr($consoante,$aux5,1);
		$str6 = substr($vogais,$aux6,1);
		$str7 = substr($numeros,$aux7,1);
		 
		$resultado .= $str1.$str2.$str3.$str4.$str5.$str6.$str7;

		$resultado = trim($resultado);
	}
	return $resultado;
}
// Função que envia e-mails
function enviaEmail($destinatario, $tipoEmail, $variaveis, $remetente=false, $idioma="pt") {
	//Adiciona algumas variáveis padrão
	global $endereco_site;
	$variaveis["url_site"]=$endereco_site;

	$modelSite=new ACKsite_Model();
	//Pega o remetente padrão do site caso não seja informado um
	if (!$remetente) {
		$dadosSite=$modelSite->dadosSite();
		$remetente=$dadosSite["email"];	
	}
	
	$enderecoSite=$modelSite->enderecoSite();
	
	//Informações sobre quebra de linha para o Header do E-mail
	if(PHP_OS == "Linux") $quebra_linha = "\n"; //Se for Linux
	elseif(PHP_OS == "WINNT") $quebra_linha = "\r\n"; // Se for Windows
	elseif(PHP_OS == "Darwin") $quebra_linha = "\n"; // Se for MacOS
	else die("Este script nao esta preparado para funcionar com o sistema operacional de seu servidor");
	
	//Define o assunto do e-mail
	$assunto = $variaveis["assunto"];

	//Define o cabeçalho do e-mail
	$headers = "MIME-Version: 1.1".$quebra_linha;
	$headers .= "Content-type: text/html; charset=utf-8".$quebra_linha;
	$headers .= "From: ".$remetente.$quebra_linha;
	$headers .= "Return-Path: ".$remetente.$quebra_linha;
	$headers .= "Reply-To: ".$variaveis["email"].$quebra_linha;
 
 	//Inclui o arquivo txt que irá formar o corpo do e-mail
	$msgFile = "includes/View/emails/".$tipoEmail."_".$idioma.".txt";
	$openFile = fopen($msgFile, 'r');
	$msgFile = fread($openFile, filesize($msgFile));
	fclose($openFile);
	
	$arrayColunas = array_keys($variaveis); // Pega as chaves do array
	
	// Separa as chaves do array e coloca elas com o # na frente para identificar como a tag que será substituida
	$tags=array();
	foreach ($arrayColunas as $tag) {
		array_push($tags, "#".$tag);
	}
	
	// Forma o array que irá faz o par de substituição das tags do TXT
	$substitutos = array();
	foreach ($variaveis as $substituto) {
		array_push($substitutos, $substituto);
	}
	
	// Faz a substituição das tags presentes no corpo de e-mail
	$corpoMsg = str_replace($tags, $substitutos, $msgFile);

	//Forma a mensagem completa - Header
	$mensagem='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="format-detection" content="telephone=no" />
<title>Comunicado Importante</title>
</head>
<body style="margin:0; padding:0;">
<table width="100%" border="0" cellspacing="30" cellpadding="0" bgcolor="white" style="-webkit-text-size-adjust:none; background-color:#FFFFFF; font-size:11px; line-height:15px; color:#666; text-align:left; font-family:Arial, Helvetica, sans-serif;">
    <tr>
        <td align="center">
            <table width="600" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td height="35"></td>
                </tr>
                <tr>
                    <td style="text-align:center;"><img src="'.$endereco_site.'/imagens/email/logo.png" alt="" border="0" style="vertical-align:bottom;" /></td>
                </tr>
                <tr>
                    <td height="5"></td>
                </tr>
                <tr>
                    <td><img src="'.$endereco_site.'/imagens/email/shadow.png" alt="" width="600" height="40" border="0" style="display:block;" /></td>
                </tr>
                <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:45px 45px 25px 45px; text-align:left;">';
			
	//Inclui o corpo do e-mail já convertido com as hashtags
	$mensagem.=$corpoMsg;
	
	//Inclui o rodapé do e-mail
	$mensagem.='		</table>
                    </td>
                </tr>
                <tr>
                    <td><img src="'.$endereco_site.'/imagens/email/shadow.png" alt="" width="600" height="40" border="0" style="display:block;" /></td>
                </tr>
                <tr>
                    <td style="text-align:center; padding:15px 0 15px 0; font-size:11px; color:#666;">'.$enderecoSite["endereco_".$idioma].'  :  '.$enderecoSite["cidade_".$idioma].'-'.$enderecoSite["estado_".$idioma].'  :  CEP '.$enderecoSite["cep_".$idioma].'  :  <a href="mailto:'.$enderecoSite["email_".$idioma].'" style="color:#666; text-decoration:none;">'.$enderecoSite["email_".$idioma].'</a><br />
                        <a href="'.$endereco_site.'" target="_blank" style="color:#666; text-decoration:none;">'.$endereco_site.'</a></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>';
	//Faz o envio do e-mail
	if (@mail($destinatario, $assunto, $mensagem, $headers)) {
		return true;
	} else {
		return false;
	}
}
// Função que verifica se uma área está protegida ou não, verificando se as sessions estão definidas
function protectedArea ($sessao=false,$retornaFalse=false,$ack=true) {
	if (!$sessao) {
		global $nome_sessao;
		$sessao=$nome_sessao;
	}
	if ($ack) {
		$sessao=$sessao."ACK";	
	}
	session_name($sessao);
	session_start();
	session_write_close();
	if (!$_SESSION["email"] and !$_SESSION["id"]) {
		//Verifica se o Cookie está setado, se tiver e estiver tudo ok, loga. Senão vai para a mensagem de erro
		if (readCookie("rU") and readCookie("rS")) {
			//Pega dos dados do Cookie
			$usuario=readCookie("rU");
			$senha=base64_decode(readCookie("rS"));
			//Forma o array com usuário e senha
			$userPass=array("usuario"=>$usuario, "senha"=>$senha);
			//Chama o Model com o SQL do banco de dados
			$model=new ACKuser_Model();
			$logado=$model->verifyUser($userPass);
			//Se estiver logado acessa a Dashboard, caso contrário limpa os Cookies e vai pra tela de Login
			if ($logado) {
				global $nome_sessao;
				global $endereco_site;
				global $tempo_sessao;
				$dados=array("resultados"=>array("email"=>$logado["email"], "id"=>$logado["id"], "nome"=>$logado["nome"], "ultimo_acesso"=>$logado["ultimo_acesso"], "expire"=>time()+$tempo_sessao));
				newSession($nome_sessao, $dados);
				$model->updateLastAccess();
				return true;	
			} else {
				closeSession();
				delCookie("rU");
				delCookie("rS");
				if($retornaFalse) {
					return false;
					exit;
				} else {
					$dadosErro["erro"]=array("titulo"=>"USUÁRIO SEM PERMISSÃO","conteudo"=>"Você não tem permissão para acessar essa página. Faça login no painel ACK para acessar.","linkACK"=>true);
					loadView("__erro",$dadosErro);
					exit;
				}
			}
		} else {
			if($retornaFalse) {
				return false;
				exit;
			} else {
				$dadosErro["erro"]=array("titulo"=>"USUÁRIO SEM PERMISSÃO","conteudo"=>"Você não tem permissão para acessar essa página. Faça login no painel ACK para acessar.","linkACK"=>true);
				loadView("__erro",$dadosErro);
				exit;
			}
		}
	} else {
		return true;	
	}
}
// Função para converter datas do formato do banco (americano) para o formato indicado
function convertDate ($data, $formato) {
	$formato=utf8_decode($formato);
	return utf8_encode(strftime($formato,strtotime($data)));
}
// Função para calcular diferença entre datas (A - Ano, M - Mês, D - Dia, H - Hora, MI - Minuto, Em branco - Segundos)
function diffDate($dataInicial, $dataFinal, $tipo='', $separadorData='-') {
	$d1 = explode($separadorData, $dataInicial);
	$d2 = explode($separadorData, $dataFinal);
	switch ($tipo) {
		case 'A':
		$X = 31536000;
		break;
		case 'M':
		$X = 2592000;
		break;
		case 'D':
		$X = 86400;
		break;
		case 'H':
		$X = 3600;
		break;
		case 'MI':
		$X = 60;
		break;
		default:
		$X = 1;
	}
	return floor(((mktime(0, 0, 0, $d2[1], $d2[2], $d2[0])-mktime(0, 0, 0, $d1[1], $d1[2], $d1[0]))/$X));
}
// Função que adiciona dias em uma data. O formato da data deve ser date("Ymd");  O formato de saída é a data em padrão americano, separado por hifens
function addDays($date,$days) {
     $thisyear = substr ( $date, 0, 4 );
     $thismonth = substr ( $date, 4, 2 );
     $thisday =  substr ( $date, 6, 2 );
     $nextdate = mktime ( 0, 0, 0, $thismonth, $thisday + $days, $thisyear );
     return strftime("%Y-%m-%d", $nextdate);
}
// Função que subtrai dias em uma data. O formato da data deve ser date("Ymd"); O formato de saída é a data em padrão americano, separado por hifens
function subDays($date,$days) {
     $thisyear = substr ( $date, 0, 4 );
     $thismonth = substr ( $date, 4, 2 );
     $thisday =  substr ( $date, 6, 2 );
     $nextdate = mktime ( 0, 0, 0, $thismonth, $thisday - $days, $thisyear );
     return strftime("%Y-%m-%d", $nextdate);
}
//Função que conta quantos itens tem na tabela definida, dá a condição do WHERE (OR ou AND) e por fim um array com as condições
function contaItens($tabela, $condicaoWhere=false, $dadosWhere=false) {
	if ($dadosWhere) {
		$where=" WHERE ";
		// Pega as chaves do array
		$arrayColunas = array_keys($dadosWhere);
		
		// Separa as chaves do array e coloca na condição WHERE no formato padrão do PDO
		$valores=array();
		foreach ($arrayColunas as $valColunas) {
			array_push($valores, $valColunas."=:".$valColunas);
		}
		$valores=implode(" ".$condicaoWhere." ", $valores);
	} else {
		$where="";
		$valores="";
	}

	// Executa o comando no banco de dados
	global $db;
	$mysql = $db->prepare('SELECT * FROM '.$tabela.$where.$valores);
	$mysql->execute($dadosWhere);
	return count($mysql->fetchAll());
}
//Função que retorna a extensão do arquivo
function extensao ($arquivo) {
	$tam = strlen($arquivo);
	//ext de 3 chars
	if( $arquivo[($tam)-4] == '.' )	{
		$extensao = substr($arquivo,-3);
	}
	
	//ext de 4 chars
	elseif( $arquivo[($tam)-5] == '.' )	{
		$extensao = substr($arquivo,-4);
	}
	
	//ext de 2 chars
	elseif( $arquivo[($tam)-3] == '.' )	{
		$extensao = substr($arquivo,-2);
	}
	
	//Caso a extensão não tenha 2, 3 ou 4 chars ele não aceita e retorna Nulo.
	else {
		$extensao = NULL;
	}
	return $extensao;
}
//Função que retorna o tamanho do arquivo
function tamanhoArquivo($arquivo) {
	global $endereco_fisico;
	$arquivo=$endereco_fisico.$arquivo;
	if (file_exists($arquivo)) {
		$tamanhoarquivo = filesize($arquivo);
		$bytes = array('KB', 'MB', 'GB', 'TB');
	
		if($tamanhoarquivo <= 999) {
			$tamanhoarquivo = 1;
		}
		
		for($i=-1; $tamanhoarquivo > 999; $i++) {
			$tamanhoarquivo = $tamanhoarquivo / 1024;
		}
		
		return round($tamanhoarquivo,2).$bytes[$i];
	} else {
		return "0KB";
	}
}
//Função que retorna o tamanho da imagem em pixels
function tamanhoImagem($arquivo) {
    $tam_img = getimagesize($arquivo);
	return array ("largura"=>$tam_img[0], "altura"=>$tam_img[1]);
}
//Função que retorna o Thumb do Youtube
function youtubeThumb($url,$tamanho="p",$cod=false,$info=false) {
	if ($tamanho=="p") {
		$tamanhoThumb="default";
	} elseif ($tamanho=="m") {
		$tamanhoThumb="hqdefault";
	} elseif ($tamanho=="g") {
		$tamanhoThumb="maxresdefault";
	}
	if(strlen($url)) {
		$pattern='#(\.be/|/embed/|/v/|/watch\?v=)([A-Za-z0-9_-]{5,11})#';
		preg_match($pattern, $url, $matches);
		$thumb="http://img.youtube.com/vi/".$matches[2]."/".$tamanhoThumb.".jpg";
		if ($info) {
		    $url = "http://gdata.youtube.com/feeds/api/videos/". $matches[2];
		    $doc = new DOMDocument;
		    $doc->load($url);
		    return $doc->getElementsByTagName($info)->item(0)->nodeValue;
		} elseif ($cod) {
			return $matches[2];
		} else {
			return $thumb;
		}
	}
}
//Função que retorna o Thumb do Vimeo
function vimeoThumb($url,$tamanho="m",$cod=false,$info=false) {
	if ($tamanho=="p") {
		$tamanhoThumb="small";
	} elseif ($tamanho=="m") {
		$tamanhoThumb="medium";
	} elseif ($tamanho=="g") {
		$tamanhoThumb="large";
	}
	if(strlen($url)) {
		$pattern="#(\.com/)([0-9]{5,11})#";
		preg_match($pattern, $url, $matches);
		$hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/".$matches[2].".php"));
		$thumb=$hash[0]["thumbnail_".$tamanhoThumb];
		if ($cod) {
			return $matches[2];
		} elseif ($info) {
			return $hash[0][$info];
		} else {
			return $thumb;
		}
	}
}
//Função que retorna o nome do Setor
function retornaSetor($idSetor,$todos=false) {
	//Pega os idiomas do Site
	$modelSite=new ACKsite_Model();
	$idioma=$modelSite->idiomasSite("1");
	
	if (!$todos) {	
		//Pega o nome do setor
		global $db;
		//$mysql = $db->prepare('SELECT * FROM setores_contato WHERE id="'.$idSetor.'";');
		$mysql = $db->prepare('SELECT * FROM setores WHERE id="'.$idSetor.'";');
		$mysql->execute();
		$resultados = $mysql->fetchAll();
		if (count($resultados)>0) {
			return $resultados[0]["titulo_".$idioma[0]["abreviatura"]];
		} else {
			return false;	
		}
	} else {
		//Retorna todos os setores
		global $db;
		$mysql = $db->prepare('SELECT * FROM setores_contato WHERE status<>"9";');
		$mysql->execute();
		$resultados = $mysql->fetchAll();
		if (count($resultados)>0) {
			return $resultados;
		} else {
			return false;	
		}
	}
}
//Função que limita o número de caracteres de uma string
function limitaCaracteres($texto,$maxchar,$end='') {
	$text=strip_tags($texto);
	if (strlen($text)>$maxchar) {
		$words=explode(" ",$text);
		$output = '';
		$i=0;
		while(1){
			$length = (strlen($output)+strlen($words[$i]));
			if($length>$maxchar){
				break;
			}else{
				$output = $output." ".$words[$i];
				++$i;
			};
		};
		return $output.$end;
	} else {
		return strip_tags($texto);
	}
}
//Função que pega uma string e retira todos os acentos e caracteres especiais dela (Usado na função formaURL)
function trocaAcentos($var) {
    $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ'); 
    $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o'); 
    $var= str_replace($a, $b, $var);
    return $var; 
}
//Função que formata uma string para um formato de URL
function formaURL($url) {
	$url = mb_strtolower(trim($url),"utf-8");
 
	$url=trocaAcentos($url);
 
	// decode html maybe needed if there's html I normally don't use this
	//$url = html_entity_decode($url,ENT_QUOTES,'UTF8');
 
	// adding - for spaces and union characters
	$find = array(' ', '&', '\r\n', '\n', '+',',');
	$url = str_replace ($find, '-', $url);
 
	//delete and replace rest of special chars
	$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
	$repl = array('', '-', '');
	$url = preg_replace ($find, $repl, $url);
 
	//return the friendly url
	return $url; 
}
//Função que pega o endereço da página
function enderecoPagina() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {
		$pageURL .= "s";
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}
//Função que retorna os dados de um item Multimedia (Imagens, vídeos anexos)
function carregaMedia($tabela,$modulo,$relacao,$idiomaVisivel="pt",$mostraTodos=false) {
	if (!$mostraTodos) {	
		//Retorna apenas o primeiro item procurado, ou false se não encontrar nada
		global $db;
		$mysql = $db->prepare('SELECT * FROM '.$tabela.' WHERE modulo="'.$modulo.'" AND relacao_id="'.$relacao.'" AND visivel_'.$idiomaVisivel.'="1" AND status="1" ORDER BY ordem ASC LIMIT 1;');
		$mysql->execute();
		$resultados = $mysql->fetchAll();
		if (count($resultados)>0) {
			return $resultados[0];
		} else {
			return false;	
		}
	} else {
		//Retorna todos os itens procurados, ou false se não encontrar nada
		global $db;
		$mysql = $db->prepare('SELECT * FROM '.$tabela.' WHERE modulo="'.$modulo.'" AND relacao_id="'.$relacao.'" AND visivel_'.$idiomaVisivel.'="1" AND status="1" ORDER BY ordem ASC;');
		$mysql->execute();
		$resultados = $mysql->fetchAll();
		if (count($resultados)>0) {
			return $resultados;
		} else {
			return false;	
		}
	}
}
//Função que retorna os dados do autor na tabela de cadastros
function carregaAutor($idAutor) {
	//Retorna todos os itens procurados, ou false se não encontrar nada
	global $db;
	$mysql = $db->prepare('SELECT * FROM cadastros WHERE id="'.$idAutor.'" AND status<>"9";');
	$mysql->execute();
	$resultados = $mysql->fetchAll();
	if (count($resultados)>0) {
		return $resultados[0];
	} else {
		return false;	
	}
}
//Função que ordena um array com a ordem dentro de outro array
function ordenaPor($array,$campo,$ordem="ASC") {
	$cod = "return strnatcmp(\$a['$campo'], \$b['$campo']);";
	if ($ordem=="ASC") {
		usort($array, create_function('$a,$b', $cod));
	} elseif($ordem=="DESC") {
		usort($array, create_function('$b,$a', $cod));
	}
	return $array;
}
//Funça que faz a paginação automatica dos conteúdos
function paginador($paginaAtual, $totalItens, $numPorPagina, $linkPaginador, $mostraSetas=false) {
	$paginaAtual=(int)$paginaAtual;
	$totalItens=(int)$totalItens;
	$numPorPagina=(int)$numPorPagina;
	
	$total_paginas = ceil($totalItens/$numPorPagina);
	if ($total_paginas>1) {
		?>
		<ol class="paginador">
        	<?
				if ($mostraSetas) {
					?>
                    <li class="seta"><button value="<?=$linkPaginador;?>/<?=$paginaAtual-1;?>"<? if ($paginaAtual<=1) { ?> disabled="disabled"<? } ?>>Anterior</button></li>
                    <?	
				}
				for ($p=1;$p<=$total_paginas;$p++) {
					?>
                    <li><button value="<?=$linkPaginador;?>/<?=$p;?>"<? if ($paginaAtual==$p) { ?> disabled="disabled"<? } ?>><?=$p;?></button></li>
                    <?
				}
				if ($mostraSetas) {
					?>
                    <li class="seta"><button value="<?=$linkPaginador;?>/<?=$paginaAtual+1;?>"<? if ($paginaAtual>=$total_paginas) { ?> disabled="disabled"<? } ?>>Próxima</button></li>
                    <?	
				}
			?>
		</ol>
		<?
	}
}
//Função que imprime o caminho da imagem com o CROP dela, se não houver crop imprime ele sem os comandos.
function mostraImagem($idImagem,$larguraPadrao="640",$alturaPadrao="480",$qualidadePadrao=false,$idioma="pt",$preencher=false,$idConteudo=false,$idModulo=false) {
	global $endereco_site;
	global $qualidade;
	
	if (!$idImagem and $idConteudo and $idModulo) {
		$dadosImagem=carregaMedia("fotos",$idModulo,$idConteudo,$idioma);
		$idImagem=$dadosImagem["id"];
	}
	
	if ($qualidadePadrao) {
		$qualidadeJPEG=$qualidadePadrao;
	} else {
		$qualidadeJPEG=$qualidade;
	}
	if ($idioma) {
		$idiomaSQL='AND visivel_'.$idioma.'="1"';
	} else {
		$idiomaSQL=false;
	}

	if ($preencher) {
		$preencherURL="&far=1&bg=".$preencher;
	} else {
		$preencherURL=false;
	}

	//Retorna todos os itens procurados, ou false se não encontrar nada
	global $db;
	$mysqlImagem = $db->prepare('SELECT * FROM fotos WHERE id="'.$idImagem.'" AND status<>"9" '.$idiomaSQL.';');
	$mysqlImagem->execute();
	$resultados = $mysqlImagem->fetchAll();
	if (empty($resultados) or !is_array($resultados) or count($resultados)==0) {
		return $endereco_site."/plugins/thumb/phpThumb.php?src=../../imagens/site/sem_imagem.png&f=png&w=".$larguraPadrao."&h=".$alturaPadrao."&q=".$qualidadeJPEG."&zc=1".$preencherURL;
		exit;
	} else {
		$dadosImagem=$resultados[0];
	}

	$mysql = $db->prepare('SELECT * FROM crops WHERE relacao_id="'.$dadosImagem["id"].'" ORDER BY id DESC LIMIT 1;');
	$mysql->execute();
	$resultadoCrop = $mysql->fetchAll();
	if (empty($resultadoCrop) or !is_array($resultadoCrop) or count($resultadoCrop)==0) {
		return $endereco_site."/plugins/thumb/phpThumb.php?src=../../galeria/".$dadosImagem["arquivo"]."&w=".$larguraPadrao."&h=".$alturaPadrao."&q=".$qualidadeJPEG."&zc=1".$preencherURL;
		exit;
	} else {
		$resultadoCrop=$resultadoCrop[0];
		return $endereco_site."/plugins/thumb/phpThumb.php?src=../../galeria/".$dadosImagem["arquivo"]."&w=".$larguraPadrao."&h=".$alturaPadrao."&aoe=1&q=".$qualidadeJPEG."&sx=".$resultadoCrop["x"]."&sy=".$resultadoCrop["y"]."&sw=".$resultadoCrop["largura"]."&sh=".$resultadoCrop["altura"].$preencherURL;
		exit;
	}
}
?>