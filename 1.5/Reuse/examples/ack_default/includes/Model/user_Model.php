<?php
class user_Model {	
	function verifyUser($dadosUser) {
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM cadastros WHERE email=:usuario AND senha=:senha AND status="1";');
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
		$mysql = $db->prepare('SELECT * FROM cadastros WHERE email=:email');
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
		$mysql = $db->prepare('SELECT * FROM cadastros WHERE '.$valores);
		$mysql->execute($dadosUser);
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno[0];
		} else {
			return false;
		}
	}
}
?>