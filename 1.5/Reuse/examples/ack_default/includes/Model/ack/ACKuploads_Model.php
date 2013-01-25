<?php
class ACKuploads_Model {	
	function listaImagens($tabela, $variaveis) {
		$arrayColunas = array_keys($variaveis); // Pega as chaves do array
		// Separa as chaves do array e coloca elas separadas por dois pontos e vírgula para definir a variável PDO dos valores
		$valores=array();
		foreach ($arrayColunas as $valColunas) {
			array_push($valores, $valColunas."=:".$valColunas);
		}
		$valores=implode(" AND ", $valores);
	
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM '.$tabela.' WHERE '.$valores.';');
		$mysql->execute($variaveis);
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno;
		} else {
			return false;
		}
	}
	function dataMidia($tabela, $variaveis) {
		$arrayColunas = array_keys($variaveis); // Pega as chaves do array
		// Separa as chaves do array e coloca elas separadas por dois pontos e vírgula para definir a variável PDO dos valores
		$valores=array();
		foreach ($arrayColunas as $valColunas) {
			array_push($valores, $valColunas."=:".$valColunas);
		}
		$valores=implode(" AND ", $valores);
	
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM '.$tabela.' WHERE '.$valores.' ORDER BY id DESC LIMIT 1;');
		$mysql->execute($variaveis);
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno[0];
		} else {
			if ($tabela=="crops") {
				return array('id'=>'0', 'largura'=>'0', 'altura'=>'0', 'x'=>'0', 'y'=>'0');
			} else {
				return false;
			}
		}
	}
}
?>