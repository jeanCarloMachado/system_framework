<?php
class ACKuser_Model {	
	function verifyUser($dadosUser) {
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM usuarios WHERE email=:usuario AND senha=:senha AND status="1" AND acessoACK="1";');
		$mysql->execute($dadosUser);
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno[0];
		} else {
			return false;
		}
	}
	function verifyEmail($dadosUser) {
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM usuarios WHERE email=:email');
		$mysql->execute($dadosUser);
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno[0];
		} else {
			return false;
		}
	}
	function dataUser($dadosUser) {
		$arrayColunas = array_keys($dadosUser); // Pega as chaves do array
		
		// Separa as chaves do array e coloca elas separadas por dois pontos e vírgula para definir a variável PDO dos valores
		$valores=array();
		foreach ($arrayColunas as $valColunas) {
			array_push($valores, $valColunas."=:".$valColunas);
		}
		$valores=implode(" AND ", $valores);

		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM usuarios WHERE '.$valores);
		$mysql->execute($dadosUser);
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno[0];
		} else {
			return false;
		}
	}
	function listaModulos() {
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM modulos WHERE ack="1";');
		$mysql->execute();
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno;
		} else {
			return false;
		}
	}
	function updateLastAccess() {
		// Executa o comando no banco de dados
		global $db;
		openSession();
		$mysql = $db->prepare('UPDATE usuarios SET ultimo_acesso=NOW() WHERE id="'.$_SESSION["id"].'"');
		$mysql->execute();
	}
	function userLevel($modulo=false, $erro404=true, $nivelLiberado=false) {
		if ($modulo=="0") {
			if ($erro404) {
				$dadosErro["erro"]=array("titulo"=>"Usuário sem permissão","conteudo"=>"Você não possui permissão de acesso a este módulo.","linkACK"=>true);
				loadView("__erro",$dadosErro);
			} else {
				return false;
			}
			exit;
		}
		// Executa o comando no banco de dados
		global $db;
		openSession();
		if ($modulo) {
			$mysql = $db->prepare('SELECT nivel FROM permissoes WHERE modulo="'.$modulo.'" AND usuario="'.$_SESSION["id"].'"');
		} else {
			$mysql = $db->prepare('SELECT permissoes.id, modulos.titulo_pt, permissoes.nivel
									FROM permissoes, modulos
									WHERE permissoes.usuario="'.$_SESSION["id"].'"
									AND permissoes.modulo=modulos.id');
		}
		@$mysql->execute();
		$nivel = $mysql->fetchAll();
		if (!$nivel or $nivel[0]["nivel"]=="0") {
			if ($erro404) {
				$dadosErro["erro"]=array("titulo"=>"Usuário sem permissão","conteudo"=>"Você não possui permissão de acesso a este módulo.","linkACK"=>true);
				loadView("__erro",$dadosErro);
				exit;
			} else {
				return false;
			}
		} else {
			if (!$nivelLiberado) {
				return $nivel[0];
			} else {
				$perAccess=(int)$nivel[0]["nivel"];
				$nivelLiberado=(int)$nivelLiberado;
				if ($perAccess>=$nivelLiberado) {
					return true;
				} else {
					if ($erro404) {
						$dadosErro["erro"]=array("titulo"=>"Usuário sem permissão","conteudo"=>"Você não possui permissão de acesso a este módulo.","linkACK"=>true);
						loadView("__erro",$dadosErro);
						exit;
					} else {
						return false;
					}
				}
			}
		}
	}
	function listaUsuarios($apartir,$limite=false,$verificaBotao=true) {
		$apartir=" LIMIT ".$apartir.", ";
		if (!$limite) {
			$modelSite=new ACKsite_Model();
			$dadosSite=$modelSite->dadosSite();
			$limite=$dadosSite["itens_pagina"];
		}
		if ($verificaBotao) {
			$limite++;
		}
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM usuarios WHERE id<>"1" AND status<>"9" '.$apartir.$limite.';');
		$mysql->execute();
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno;
		} else {
			return false;
		}
	}
}
?>