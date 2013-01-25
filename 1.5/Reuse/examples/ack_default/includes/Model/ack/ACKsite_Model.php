<?php
class ACKsite_Model {	
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
	function visitasSite($tipo=false) {
		// Executa o comando no banco de dados
		global $db;
		if ($tipo=="total") {
			$mysql = $db->prepare('SELECT * FROM visitas;');
			$mysql->execute();
			$retorno = $mysql->fetchAll();
			return array("total"=>count($retorno));
		} elseif ($tipo=="mensal") {
			$mysql = $db->prepare('SELECT * FROM visitas WHERE data>="'.date("Y").'-'.date("m").'-01" AND data<="'.date("Y").'-'.date("m").'-31";');
			$mysql->execute();
			$retorno = $mysql->fetchAll();
			return array("mensal"=>count($retorno));
		} else {
			$mysqlTotal = $db->prepare('SELECT * FROM visitas;');
			$mysqlTotal->execute();
			$retornoTotal = $mysqlTotal->fetchAll();

			$mysqlMensal = $db->prepare('SELECT * FROM visitas WHERE data>="'.date("Y").'-'.date("m").'-01" AND data<="'.date("Y").'-'.date("m").'-31";');
			$mysqlMensal->execute();
			$retornoMensal = $mysqlMensal->fetchAll();
			return array("mensal"=>count($retornoMensal),"total"=>count($retornoTotal));
		}
	}
	function enderecoSite() {
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM enderecos ORDER BY id ASC LIMIT 1;');
		$mysql->execute();
		$dados = $mysql->fetchAll();
		return $dados[0];
	}
	function listaEnderecos() {
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM enderecos WHERE status<>"9" ORDER BY ordem ASC;');
		$mysql->execute();
		$dados=$mysql->fetchAll();
		if (!empty($dados[0])) { 
			return $dados;
		} else {
			return false;
		}
	}
	function dataEndereco($dados) {		
		$arrayColunas = array_keys($dados); // Pega as chaves do array
		
		// Separa as chaves do array e coloca elas separadas por dois pontos e vírgula para definir a variável PDO dos valores
		$valores=array();
		foreach ($arrayColunas as $valColunas) {
			array_push($valores, $valColunas."=:".$valColunas);
		}
		$valores=implode(" AND ", $valores);

		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM enderecos WHERE '.$valores);
		$mysql->execute($dados);
		$retorno = $mysql->fetchAll();
		if (!empty($retorno[0])) {
			return $retorno[0];
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
	function idiomasSite($mostrarQts=false) {
		if ($mostrarQts) {
			$limit=" LIMIT ".$mostrarQts;
		}
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM idiomas ORDER BY id ASC'.$limit.';');
		$mysql->execute();
		return $mysql->fetchAll();
	}
	function conteudoIdioma($tabela,$id,$obrigatorio) {
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM idiomas ORDER BY id ASC;');
		$mysql->execute();
		$idiomas=$mysql->fetchAll();
		$listaIdiomas=array();
		foreach ($idiomas as $idioma) {
			if ($id) {
				$mysqlConteudo = $db->prepare('SELECT * FROM '.$tabela.' WHERE id="'.$id.'" AND '.$obrigatorio.'_'.$idioma["abreviatura"].'<>"";');
			} else {
				$mysqlConteudo = $db->prepare('SELECT * FROM '.$tabela.' WHERE '.$obrigatorio.'_'.$idioma["abreviatura"].'<>"";');
			}
			$mysqlConteudo->execute();
			$conteudo=$mysqlConteudo->fetchAll();
			if (!empty($conteudo)) {
				$item=array("nome"=>$idioma["nome"],"abreviatura"=>$idioma["abreviatura"],"class"=>"completo");
				array_push($listaIdiomas,$item);
			} else {
				$item=array("nome"=>$idioma["nome"],"abreviatura"=>$idioma["abreviatura"],"class"=>"");
				array_push($listaIdiomas,$item);
			}
		}
		return $listaIdiomas;
	}
	function idModulo($modulo,$ackModulo=false) {
		if ($ackModulo) {
			$ack='AND ack="1"';
		} else {
			$ack=false;
		}
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM modulos WHERE modulo="'.$modulo.'" '.$ack.' AND status="1" ORDER BY id ASC LIMIT 1;');
		$mysql->execute();
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno[0]["id"];
		} else {
			return "0";
		}
	}
	function modulosDestaque() {
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM modulos WHERE destaque="1" AND status="1" ORDER BY id ASC;');
		$mysql->execute();
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno;
		} else {
			return "0";
		}
	}
	function ajustaOrdem($tabela, $posicao_nova, $posicao_antiga, $categoria=false) {
		global $db;

		//Forma os tipos de ordem do ajuste
		if ($posicao_nova<$posicao_antiga) {
			$sql='SELECT * FROM '.$tabela.' WHERE ordem>="'.$posicao_nova.'" AND ordem<"'.$posicao_antiga.'" AND status<>"9" ORDER BY ordem ASC;';
			$tipoOrdem="soma";
		} elseif ($posicao_nova>$posicao_antiga) {
			$sql='SELECT * FROM '.$tabela.' WHERE ordem<="'.$posicao_nova.'" AND ordem>"'.$posicao_antiga.'" AND status<>"9" ORDER BY ordem ASC;';
			$tipoOrdem="subtrai";		
		} else {
			return false;
			exit;	
		}
		
		if ($categoria) {
			$modelGeral=new ACKgeral_Model();
			$itens=$modelGeral->listaItens($tabela,0,99999999999999,false,$categoria);
			foreach ($itens as $item) {	
				$categorias=unserialize($item["categorias"]);
				if (is_array($categorias)) {
					foreach ($categorias as $idCategoria => $ordem) {
						if ($tipoOrdem=="soma") {
							(int)$novaOrdem=$ordem+1;
							if ($idCategoria==$categoria and $ordem>=$posicao_nova and $ordem<$posicao_antiga) {
								$categorias[$idCategoria]=$novaOrdem;
							}
						} elseif ($tipoOrdem=="subtrai") {
							(int)$novaOrdem=$ordem-1;
							if ($idCategoria==$categoria and $ordem<=$posicao_nova and $ordem>$posicao_antiga) {
								$categorias[$idCategoria]=$novaOrdem;
							}
						}
					}
				}
				$catSerial=serialize($categorias);
				$mysqlUpdt = $db->prepare('UPDATE '.$tabela.' SET categorias="'.$catSerial.'" WHERE id="'.$item["id"].'";');
				$mysqlUpdt->execute();
			}
			return true;		
		} else {
			// Executa o comando no banco de dados
			$mysql = $db->prepare($sql);
			$mysql->execute();
			$itens = $mysql->fetchAll();
			foreach ($itens as $item) {
				if ($tipoOrdem=="soma") {
					$novaOrdem=$item["ordem"]+1;
				} elseif ($tipoOrdem=="subtrai") {
					$novaOrdem=$item["ordem"]-1;
				}
				$mysqlUpdt = $db->prepare('UPDATE '.$tabela.' SET ordem="'.$novaOrdem.'" WHERE id="'.$item["id"].'";');
				$mysqlUpdt->execute();
			}
			return true;
		}
	}
}
?>