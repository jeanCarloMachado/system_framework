<?php
class ACKtextos_Model {	
	function carregaTexto($id,$completo=false) {
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM textosack WHERE id="'.$id.'";');
		$mysql->execute();
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			if ($completo) {
				return $retorno[0];
			} else {
				return $retorno[0]["texto"];
			}
		} else {
			return false;
		}
	}
	function listaTextos($tipo=false) {
		if ($tipo) {
			$where="WHERE tipo='".$tipo."'";
		} else {
			$where="";	
		}
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM textosack '.$where.';');
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