<?php
class site_Model {	
	function dadosSite() {
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM sistema;');
		$mysql->execute();
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno[0];
		} else {
			return false;
		}
	}
	function listaEnderecos($id) {
		if ($id) {
			$where='AND id="'.$id.'"';
		} else {
			$where="";
		}
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM enderecos WHERE status="1" AND visivel="1" '.$where.' ORDER BY id ASC;');
		$mysql->execute();
		$dados=$mysql->fetchAll();
		if (count($dados)) { 
			if ($id) {
				return $dados[0];
			} else {
				return $dados;
			}
		} else {
			return false;
		}
	}
	function listaEstados() {
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM estados ORDER BY sigla ASC;');
		$mysql->execute();
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno;
		} else {
			return false;
		}
	}
	function listaSetores($id) {
		if ($id) {
			$where='AND id="'.$id.'"';
		} else {
			$where="";
		}
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM setores WHERE status="1" AND visivel="1" '.$where.' ORDER BY ordem ASC;');
		$mysql->execute();
		$dados=$mysql->fetchAll();
		if (count($dados)) { 
			if ($id) {
				return $dados[0];
			} else {
				return $dados;
			}
		} else {
			return false;
		}
	}
	function metaTagsSite($tabela,$id) {
		$dados=array("tabela"=>$tabela,"item"=>$id);
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM metatags WHERE tabela=:tabela AND item=:item ORDER BY id ASC LIMIT 1;');
		$mysql->execute($dados);
		$dados=$mysql->fetchAll();
		if (count($dados)) { 
			return $dados[0];
		} else {
			return false;
		}
	}
	function idModulo($modulo,$ackModulo=false) {
		if ($ackModulo) {
			$ack="1";
		} else {
			$ack="0";
		}
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM modulos WHERE modulo="'.$modulo.'" AND ack="'.$ack.'" ORDER BY id ASC LIMIT 1;');
		$mysql->execute();
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno[0]["id"];
		} else {
			return "0";
		}
	}
	function somaAcesso() {
		$ip=gethostbyname($_SERVER['REMOTE_ADDR']);
		$dataLimite=date('Y-m-d H:i:s',strtotime('-12 hour'));
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM visitas WHERE ip="'.$ip.'" AND data>="'.$dataLimite.'"  ORDER BY id DESC LIMIT 1;');
		$mysql->execute();
		$ultimoAcesso = $mysql->fetchAll();
		if (!$ultimoAcesso[0]) {
			dbSave("visitas", array("resultados"=>array("ip"=>$ip)),false,false);
		}
	}
}
?>