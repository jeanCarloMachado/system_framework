<?php
class geral_Model {	
	####################################################################
	## Categorias
	####################################################################
	function listaCategorias($modulo,$subcategoria) {
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM categorias WHERE modulo="'.$modulo.'" AND relacao_id="'.$subcategoria.'" AND visivel="1" AND status<>"9" ORDER BY relacao_id ASC, ordem ASC;');
		$mysql->execute();
		$resultados = $mysql->fetchAll();
		if (count($resultados)>0) {
			return $resultados;
		} else {
			return false;
		}
	}
	####################################################################
	## Destaques
	####################################################################
	function listaDestaques($modulo) {
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM destaques WHERE modulo="'.$modulo.'" AND visivel="1" AND status<>"9" ORDER BY ordem ASC;');
		$mysql->execute();
		$resultados = $mysql->fetchAll();
		if (count($resultados)>0) {
			return $resultados;
		} else {
			return false;
		}
	}
	####################################################################
	## Itens com categorias (Produtos, Receitas, Notícias)
	####################################################################
	function listaItens($tabela,$apartir,$limite=false,$orderBy,$sentido,$categoria=false) {
		if (!$limite) {
			$modelSite=new site_Model();
			$dadosSite=$modelSite->dadosSite();
			$limite=$dadosSite["itens_pagina"];
		}
		if ($verificaBotao) {
			$limite++;
		}
		if (!$categoria) {
			$apartirWhere=" LIMIT ".$apartir.", ".$limite;
		} else {
			$apartirWhere=false;
		}
		if ($orderBy) {
			$orderByWhere='ORDER BY '.$orderBy.' '.$sentido;
		}
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM '.$tabela.' WHERE visivel="1" AND status<>"9" '.$orderByWhere.' '.$apartirWhere.';');
		$mysql->execute();
		$resultados = $mysql->fetchAll();
		if ($categoria) {
			$produtos=array();
			$itens=0;
			foreach ($resultados as $resultado) {
				$categorias=unserialize($resultado["categorias"]);
				if (is_array($categorias) and $categorias<>"" and $categorias!=false) {
					foreach ($categorias as $idCategoria => $ordem) {
						if ($idCategoria==$categoria) {
							if (count($produtos)<=$limite) {
								if ($itens>=$apartir) {
									$resultado["ordem"]=$this->ordemItem($tabela, $resultado["id"],$categoria);
									array_push($produtos, $resultado);
								}
								$itens++;
							}
						}
					}
				} else {
					if ($categoria==$resultado["categoria"]) {
						if (count($produtos)<=$limite) {
							if ($itens>=$apartir) {
								array_push($produtos, $resultado);
							}
							$itens++;
						}
					}
				}
			}
			if (count($produtos)>0) {
				return ordenaPor($produtos, "ordem");
			} else {
				return false;
			}
		} else {
			if (count($resultados)>0) {
				return $resultados;
			} else {
				return false;
			}
		}
	}
	function ordemItem($tabela, $idProduto,$idCategoria=false) {
		if ($idCategoria) {
			// Executa o comando no banco de dados
			global $db;
			$mysql = $db->prepare('SELECT * FROM '.$tabela.' WHERE id="'.$idProduto.'" AND status<>"9" LIMIT 1;');
			$mysql->execute();
			$retorno = $mysql->fetchAll();
			$categorias=unserialize($retorno[0]["categorias"]);
			$ordemProduto=false;	
			foreach ($categorias as $categoria => $ordem) {
				if ($categoria==$idCategoria) {
					$ordemProduto=$ordem;
				}
			}
			if ($ordemProduto) {
				return $ordemProduto;	
			} else {
				return "0";
			}
		} else {
			return "0";	
		}
	}
	####################################################################
	## Genérico (usa um select tabela e um array de valores dinamicos)
	####################################################################
	function listaSQL($sql) {
		global $db;
		$mysql = $db->prepare($sql);
		$mysql->execute();
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno;
		} else {
			return false;
		}
	}
	####################################################################
	## Cadastros
	####################################################################
	function dataCadastro($dadosCadastro) {
		$arrayColunas = array_keys($dadosCadastro); // Pega as chaves do array
		
		// Separa as chaves do array e coloca elas separadas por dois pontos e vírgula para definir a variável PDO dos valores
		$valores=array();
		foreach ($arrayColunas as $valColunas) {
			array_push($valores, $valColunas."=:".$valColunas);
		}
		array_push($valores, 'status<>"9"');
		$valores=implode(" AND ", $valores);

		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM cadastros WHERE '.$valores);
		$mysql->execute($dadosCadastro);
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno[0];
		} else {
			return false;
		}
	}
}
?>