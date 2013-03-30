<?php
class ACKgeral_Model {	
	####################################################################
	## Destaques do Site
	####################################################################
	function listaDestaques($apartir,$limite=false,$verificaBotao=true) {
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
		$mysql = $db->prepare('SELECT * FROM destaques WHERE status<>"9" ORDER BY ordem ASC '.$apartir.$limite.';');
		$mysql->execute();
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno;
		} else {
			return false;
		}
	}
	function dataDestaque($dadosUser) {
		$arrayColunas = array_keys($dadosUser); // Pega as chaves do array
		
		// Separa as chaves do array e coloca elas separadas por dois pontos e vírgula para definir a variável PDO dos valores
		$valores=array();
		foreach ($arrayColunas as $valColunas) {
			array_push($valores, $valColunas."=:".$valColunas);
		}
		$valores=implode(" AND ", $valores);

		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM destaques WHERE '.$valores);
		$mysql->execute($dadosUser);
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno[0];
		} else {
			return false;
		}
	}
	####################################################################
	## Tópicos Institucional
	####################################################################
	function listaTopicos($apartir,$limite=false,$verificaBotao=true) {
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
		$mysql = $db->prepare('SELECT * FROM institucional WHERE status<>"9" ORDER BY ordem ASC '.$apartir.$limite.';');
		$mysql->execute();
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno;
		} else {
			return false;
		}
	}
	function dataTopico($dadosUser) {
		$arrayColunas = array_keys($dadosUser); // Pega as chaves do array
		
		// Separa as chaves do array e coloca elas separadas por dois pontos e vírgula para definir a variável PDO dos valores
		$valores=array();
		foreach ($arrayColunas as $valColunas) {
			array_push($valores, $valColunas."=:".$valColunas);
		}
		$valores=implode(" AND ", $valores);

		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM institucional WHERE '.$valores);
		$mysql->execute($dadosUser);
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno[0];
		} else {
			return false;
		}
	}
	####################################################################
	## Categorias (Produtos, Imprensa, etc...)
	####################################################################
	function listaCategorias($modulo,$subcategoria) {
		if ($subcategoria!==false) {
			$sql='SELECT * FROM categorias WHERE modulo="'.$modulo.'" AND relacao_id="'.$subcategoria.'" AND status<>"9" ORDER BY relacao_id ASC, ordem ASC;';
		} else {
			$sql='SELECT * FROM categorias WHERE modulo="'.$modulo.'" AND status<>"9" ORDER BY relacao_id ASC, ordem ASC;';
		}
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare($sql);
		$mysql->execute();
		$resultados = $mysql->fetchAll();
		if (count($resultados)>0) {
			return $resultados;
		} else {
			return false;
		}
	}
	function dataCategoria($dados) {
		$arrayColunas = array_keys($dados); // Pega as chaves do array
		
		// Separa as chaves do array e coloca elas separadas por dois pontos e vírgula para definir a variável PDO dos valores
		$valores=array();
		foreach ($arrayColunas as $valColunas) {
			array_push($valores, $valColunas."=:".$valColunas);
		}
		$valores=implode(" AND ", $valores);

		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM categorias WHERE '.$valores);
		$mysql->execute($dados);
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno[0];
		} else {
			return false;
		}
	}
	####################################################################
	## Produtos, Notícias, etc (Módulos com Categorias)
	####################################################################
	function qtdadeItens($tabela,$categoria,$total=false,$retornaValores=false,$semCategoria=false) {
		// Executa o comando no banco de dados
		global $db;
		if ($semCategoria==true){
			$querySQL='SELECT * FROM '.$tabela.' WHERE status<>"9" AND categorias="";';
		} else {
			$querySQL='SELECT * FROM '.$tabela.' WHERE status<>"9";';
		}
		$mysql = $db->prepare($querySQL);
		$mysql->execute();
		$resultados = $mysql->fetchAll();	
		if ($retornaValores) {
			return $resultados;
		} else {
			if ($total==true) {
				return count($resultados);
			} else {
				$contagem=0;
				foreach ($resultados as $resultado) {
					$categorias=unserialize($resultado["categorias"]);
					if (is_array($categorias)) {
						foreach ($categorias as $idCategoria => $ordem) {
							if ($idCategoria==$categoria) {
								$contagem++;
							}
						}
					} else {
						if ($categoria==$resultado["categoria"]) {
							$contagem++;
						}
					}
				}
				return $contagem;
			}
		}
	}
	function listaItens($tabela,$apartir,$limite=false,$verificaBotao=true,$categoria=false, $orderBy=false) {
		if (!$limite) {
			$modelSite=new ACKsite_Model();
			$dadosSite=$modelSite->dadosSite();
			$limite=$dadosSite["itens_pagina"];
		}
		if ($verificaBotao) {
			$limite++;
		}
		if (!$categoria or $categoria=="semCategoria") {
			$apartirWhere=" LIMIT ".$apartir.", ".$limite;
		} else {
			$apartirWhere=false;
		}
		if ($orderBy) {
			$orderWhere="ORDER BY ".$orderBy;	
		} else {
			$orderWhere="";
		}
		// Executa o comando no banco de dados
		global $db;
		if ($categoria=="semCategoria") {
			$querySQL='SELECT * FROM '.$tabela.' WHERE status<>"9" AND categorias="" '.$orderWhere.' '.$apartirWhere.';';
		} else {
			$querySQL='SELECT * FROM '.$tabela.' WHERE status<>"9" '.$orderWhere.' '.$apartirWhere.';';
		}
		$mysql = $db->prepare($querySQL);
		$mysql->execute();
		$resultados = $mysql->fetchAll();
		if ($categoria==true and $categoria!="semCategoria") {
			$produtos=array();
			$itens=0;
			foreach ($resultados as $resultado) {
				$categorias=unserialize($resultado["categorias"]);
				if (is_array($categorias) and $categorias<>"" and $categorias!=false) {
					foreach ($categorias as $idCategoria => $ordem) {
						if ($idCategoria==$categoria) {
							if (count($produtos)<=$limite) {
								if ($itens>=$apartir) {
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
				return $produtos;
			} else {
				return false;
			}
		} else {
			if (!empty($resultados) and count($resultados)>0) {
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
	function dataItem($tabela, $dadosProduto) {
		$arrayColunas = array_keys($dadosProduto); // Pega as chaves do array
		
		// Separa as chaves do array e coloca elas separadas por dois pontos e vírgula para definir a variável PDO dos valores
		$valores=array();
		foreach ($arrayColunas as $valColunas) {
			array_push($valores, $valColunas."=:".$valColunas);
		}
		$valores=implode(" AND ", $valores);

		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM '.$tabela.' WHERE '.$valores);
		$mysql->execute($dadosProduto);
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno[0];
		} else {
			return false;
		}
	}
	####################################################################
	## Contatos
	####################################################################
	function listaContatos($apartir,$limite=false,$verificaBotao=true,$lidos=false) {
		$apartir=" LIMIT ".$apartir.", ";
		if (!$limite) {
			$modelSite=new ACKsite_Model();
			$dadosSite=$modelSite->dadosSite();
			$limite=$dadosSite["itens_pagina"];
		}
		if ($verificaBotao) {
			$limite++;
		}
		if ($lidos!==false) {
			$lidosWhere=' AND lido="'.$lidos.'"';
		} else {
			$lidosWhere="";	
		}
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM contatos WHERE status<>"9" '.$lidosWhere.' ORDER BY data DESC '.$apartir.$limite.';');
		$mysql->execute();
		$resultados = $mysql->fetchAll();
		if (count($resultados)>0) {
			return $resultados;
		} else {
			return false;
		}
	}
	function dataContato($dados) {
		$arrayColunas = array_keys($dados); // Pega as chaves do array
		
		// Separa as chaves do array e coloca elas separadas por dois pontos e vírgula para definir a variável PDO dos valores
		$valores=array();
		foreach ($arrayColunas as $valColunas) {
			array_push($valores, $valColunas."=:".$valColunas);
		}
		$valores=implode(" AND ", $valores);

		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM contatos WHERE '.$valores.' AND status<>"9";');
		$mysql->execute($dados);
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno[0];
		} else {
			return false;
		}
	}
	####################################################################
	## Newsletter
	####################################################################
	function listaNewsletter($apartir,$limite=false,$verificaBotao=true,$data=false) {
		$apartir=" LIMIT ".$apartir.", ";
		if (!$limite) {
			$modelSite=new ACKsite_Model();
			$dadosSite=$modelSite->dadosSite();
			$limite=$dadosSite["itens_pagina"];
		}
		if ($verificaBotao) {
			$limite++;
		}
		if ($data) {
			$where="AND data_inc>='".convertDate($data,"%Y-%m-%d")."'";
		} else {
			$where="";
		}
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM newsletter WHERE status<>"9" '.$where.' ORDER BY data_inc DESC '.$apartir.$limite.';');
		$mysql->execute();
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno;
		} else {
			return false;
		}
	}
	####################################################################
	## Módulos do Site
	####################################################################
	function listaModulos($apartir,$limite=false,$verificaBotao=true) {
		$modelSite=new ACKsite_Model();
		$idiomas=$modelSite->idiomasSite();
		if (count($idiomas)>0) {
			$where=" AND ( ";
			$i=0;
			foreach ($idiomas as $idioma) {
				if ($i==0) {
					$where=$where." titulo_".$idioma["abreviatura"]."<>''";
				} else {
					$where=$where." OR titulo_".$idioma["abreviatura"]."<>''";
				}
				$i++;
			}
			$where=$where." )";
		} else {
			$where="";
		}

		$apartir=" LIMIT ".$apartir.", ";
		if (!$limite) {
			$dadosSite=$modelSite->dadosSite();
			$limite=$dadosSite["itens_pagina"];
		}
		if ($verificaBotao) {
			$limite++;
		}
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM modulos WHERE ack="0" '.$where.' ORDER BY id ASC '.$apartir.$limite.';');
		$mysql->execute();
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno;
		} else {
			return false;
		}
	}
	function dataModulo($dadosModulo) {
		$arrayColunas = array_keys($dadosModulo); // Pega as chaves do array
		
		// Separa as chaves do array e coloca elas separadas por dois pontos e vírgula para definir a variável PDO dos valores
		$valores=array();
		foreach ($arrayColunas as $valColunas) {
			array_push($valores, $valColunas."=:".$valColunas);
		}
		$valores=implode(" AND ", $valores);

		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM modulos WHERE '.$valores);
		$mysql->execute($dadosModulo);
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno[0];
		} else {
			return false;
		}
	}
	####################################################################
	## Setores de Contato
	####################################################################
	function listaSetores($apartir,$limite=false,$verificaBotao=true) {
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
		$mysql = $db->prepare('SELECT * FROM setores WHERE status<>"9" '.$apartir.$limite.';');
		$mysql->execute();
		$retorno = $mysql->fetchAll();
		if (count($retorno)>0) {
			return $retorno;
		} else {
			return false;
		}
	}
	function dataSetor($dadosUser) {
		$arrayColunas = array_keys($dadosUser); // Pega as chaves do array
		
		// Separa as chaves do array e coloca elas separadas por dois pontos e vírgula para definir a variável PDO dos valores
		$valores=array();
		foreach ($arrayColunas as $valColunas) {
			array_push($valores, $valColunas."=:".$valColunas);
		}
		$valores=implode(" AND ", $valores);

		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT * FROM setores WHERE '.$valores);
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