<?php
class ACKnoticias_Controller
{
	function index () {
		protectedArea();
		openSession();
		verifyTimeSession();
		$dados=array();
		//Pega os dados do Site
		$modelSite=new ACKsite_Model();
		$dadosSite=$modelSite->dadosSite();
		$dados["dadosSite"]=$dadosSite;
		(int)$limite=$dadosSite["itens_pagina"];
		$dados["plugins"]=true;
		$dados["idioma"]=$modelSite->idiomasSite("1");

		//Carrega o ID do Módulo
		$moduloVerifica=$modelSite->idModulo("noticias",false);

		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true);	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));

		//Pega os dados das Notícias
		$modelGeral=new ACKgeral_Model();
		$totalNoticias=$modelGeral->qtdadeItens("noticias", "0", true);
		$totalSemCategoria=$modelGeral->qtdadeItens("noticias","0",true,false,true);

		//Função para listar as categorias
		function listaCategoria($modulo,$subcategoria,&$arrayInt) {
			$modelGeral=new ACKgeral_Model();
			$resultados=$modelGeral->listaCategorias($modulo,$subcategoria);
			if ($resultados) {
				foreach ($resultados as $resultado) {
					$qtdadeNoticias=$modelGeral->qtdadeItens("noticias", $resultado["id"]);
					array_push($arrayInt, array('id'=>$resultado["id"], 'nome'=>$resultado["titulo_pt"], 'qtdadeNoticias'=>$qtdadeNoticias));
					listaCategoria($modulo,$resultado["id"],$arrayInt);
				}
			}
		}
		$arrayCategoria=array();
		array_push($arrayCategoria, array('id'=>"0", 'nome'=>"Mostrar Todas", 'qtdadeNoticias'=>$totalNoticias));
		if ($totalSemCategoria>"0") {
			array_push($arrayCategoria, array('id'=>"semCategoria", 'nome'=>"Sem Categoria", 'qtdadeNoticias'=>$totalSemCategoria));
		}
		listaCategoria($moduloVerifica,"0",$arrayCategoria);
		$dados["categorias"]=$arrayCategoria;

		$dados["tituloPagina"]="Notícias";
		loadView("ack/noticias",$dados);
	}
	function excluir($dadosJSON) {
		postRequest();
		$dadosJSON=readJSON($_POST["ajaxACK"]);
		protectedArea();
		openSession();
		verifyTimeSession();
		$itens_lista=explode(",",$dadosJSON["itens_lista"]);

		//Carrega o ID do Módulo
		$modelSite=new ACKsite_Model();
		$moduloVerifica=$modelSite->idModulo("noticias",false);
		
		$modelUser=new ACKuser_Model();
		if(!$modelUser->userLevel($moduloVerifica, false, "2")) {
			$json['status']=0;
			$json["mensagem"]="Usuário sem permissão.";
		} else {	
			$total=0;
			$json["array"]=array();
			foreach ($itens_lista as $item) {
				dbDelete("noticias", $item);
				array_push($json["array"], $item);
				$total++;
			}
			$json['status']=1;
			$json["total"]=$total;
		}
		echo newJSON($json);
	}
    function incluir () {
		protectedArea();
		openSession();
		verifyTimeSession();
		$dados=array();
		//Pega os dados do Site
		$modelSite=new ACKsite_Model();
		$dadosSite=$modelSite->dadosSite();
		$dados["dadosSite"]=$dadosSite;
		$dados["plugins"]=true;
		$dados["conteudoIdiomas"]=$modelSite->idiomasSite();

		//Carrega o ID do Módulo
		$moduloVerifica=$modelSite->idModulo("noticias",false);

		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true,"2");	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));

		//Carrega algumas opções da View
		$dados["tipoAcao"]="incluir";
		$dados["tituloPagina"]="Adicionar Notícia";
		$dados["plugins"]=true;
		$dados["multiplasImagens"]=true; //True se puder enviar várias imagens, false se puder enviar apenas uma
		$dados["tamanhoCrop"]="500 400"; //String com a largura (espaço) altura do crop, ou false se não houver crop
		$dados["abaImagens"]=true; //True se aba imagens deve estar visível ou false se não deve
		$dados["abaVideos"]=true; //True se aba imagens deve estar visível ou false se não deve
		$dados["abaAnexos"]=true; //True se aba imagens deve estar visível ou false se não deve
		
		//Função para listar as categorias
		function listaCategoria($modulo,$subcategoria,&$arrayInt) {
			$modelGeral=new ACKgeral_Model();
			$resultados=$modelGeral->listaCategorias($modulo,$subcategoria);
			if ($resultados) {
				foreach ($resultados as $resultado) {
					array_push($arrayInt, array('id'=>$resultado["id"], 'nome'=>$resultado["titulo_pt"]));
					listaCategoria($modulo,$resultado["id"],$arrayInt);
				}
			}
		}
		$arrayCategoria=array();
		listaCategoria($moduloVerifica,"0",$arrayCategoria);
		$dados["categorias"]=$arrayCategoria;

		//Nível de permissão das Metatags
		$moduloVerificaMetatags=$modelSite->idModulo("metatags_ack",true);
		$dados["nivelMetatags"]=$modelUser->userLevel($moduloVerificaMetatags,false, false);

		//Nivel de permissão dos dados do produto
		$dados["nivelPermissao"]="2";	
		
		//Carrega a View
		loadView("ack/noticia",$dados);
	}
    function editar ($dadosURL) {
		$id=$dadosURL[0];
		protectedArea();
		openSession();
		verifyTimeSession();
		$dados=array();
		//Pega os dados do Site
		$modelSite=new ACKsite_Model();
		$dadosSite=$modelSite->dadosSite();
		$dados["dadosSite"]=$dadosSite;
		$dados["plugins"]=true;
		$dados["conteudoIdiomas"]=$modelSite->conteudoIdioma("noticias",$id,"titulo");
		$dados["multiplasImagens"]=true; //True se puder enviar várias imagens, false se puder enviar apenas uma
		$dados["tamanhoCrop"]="500 400"; //String com a largura (espaço) altura do crop, ou false se não houver crop
		$dados["abaImagens"]=true; //True se aba imagens deve estar visível ou false se não deve
		$dados["abaVideos"]=true; //True se aba imagens deve estar visível ou false se não deve
		$dados["abaAnexos"]=true; //True se aba imagens deve estar visível ou false se não deve

		//Carrega o ID do Módulo
		$moduloVerifica=$modelSite->idModulo("noticias",false);

		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true,"1");	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));

		//Função para listar as categorias
		function listaCategoria($modulo,$subcategoria,&$arrayInt) {
			$modelGeral=new ACKgeral_Model();
			$resultados=$modelGeral->listaCategorias($modulo,$subcategoria);
			if ($resultados) {
				foreach ($resultados as $resultado) {
					array_push($arrayInt, array('id'=>$resultado["id"], 'nome'=>$resultado["titulo_pt"]));
					listaCategoria($modulo,$resultado["id"],$arrayInt);
				}
			}
		}
		$arrayCategoria=array();
		listaCategoria($moduloVerifica,"0",$arrayCategoria);
		$dados["categorias"]=$arrayCategoria;
				
		//Pega os dados do Noticia
		$modelGeral=new ACKgeral_Model();
		$dados["dadosNoticia"]=$modelGeral->dataItem("noticias", array("id"=>$id));
		if (!$dados["dadosNoticia"]) {
			$dadosErro["erro"]=array("titulo"=>"NOTÍCIA NÃO EXISTE","conteudo"=>"A notícia que você tentou acessar não existe ou foi excluído.","linkACK"=>true);
			loadView("__erro",$dadosErro);
			exit;
		}
		
		//Pega os dados das Metatags e algumas opções da View
		$dados["metatags"]=$modelSite->metaTagsSite("noticias",$id);
		$dados["plugins"]=true;
		$dados["tipoAcao"]="editar";
		$dados["tituloPagina"]="Editar Notícia";

		//Libera o acesso para edição do conteúdo, ou apenas visualização
		$nivelPermissao=$modelUser->userLevel($moduloVerifica,false);
		$dados["nivelPermissao"]=$nivelPermissao["nivel"];

		//Nível de permissão das Metatags
		$moduloVerificaMetatags=$modelSite->idModulo("metatags_ack",true);
		$dados["nivelMetatags"]=$modelUser->userLevel($moduloVerificaMetatags,false, false);
		
		//Carrega a View
		loadView("ack/noticia",$dados);
	}
	function salvar() {
		postRequest();
		$dadosJSON=readJSON($_POST["ajaxACK"]);
		protectedArea();
		openSession();
		verifyTimeSession();
		
		//Carrega o ID do Módulo
		$modelSite=new ACKsite_Model();
		$moduloVerifica=$modelSite->idModulo("noticias",false);

		$modelUser=new ACKuser_Model();
		if(!$modelUser->userLevel($moduloVerifica,false,"2")) {
			$json['status'] = 0;
			$json['mensagem'] = "Usuário sem permissão para salvar notícias. ";
		} else {

			if ($dadosJSON["acao"]=="incluir") {
				//Salva o Tópico
				$dados["resultados"]=$dadosJSON["noticias"];
				$dados["resultados"]["data"]=convertDate($dadosJSON["noticias"]["data"], "%Y-%m-%d");
				$dados["resultados"]["visivel"]=$dadosJSON["visivel"];
				$dados["resultados"]["status"]="1";
				$categorias=array();
				foreach ($dadosJSON["categorias"] as $categoria) {
					$ordem = proximaOrdem("noticias", array("categorias"=>$categoria, "status"=>"1"), true);
					$categorias[$categoria]=$ordem;
				}
				if (!empty($categorias)) {
					$dados["resultados"]["categorias"]=serialize($categorias);
				} else {
					$dados["resultados"]["categorias"]="";
					$dados["resultados"]["visivel"]="0";
				}
				$idItem=dbSave("noticias",$dados,true);
				
				//Cria a Meta-tag
				$dadosMetaTag["resultados"]["tabela"]="noticias";
				$dadosMetaTag["resultados"]["item"]=$idItem;
				dbSave("metatags",$dadosMetaTag);
				
				//Gera os retornos do JSON
				$json['status'] = 1;
				$json['mensagem']= "Dados salvos com sucesso! ";
				$json['id'] = $idItem;
			} elseif ($dadosJSON["acao"]=="editar") {
				// Salva o tópico
				$dados["resultados"]=$dadosJSON["noticias"];
				$dados["resultados"]["data"]=convertDate($dadosJSON["noticias"]["data"], "%Y-%m-%d");
				foreach ($dadosJSON["categorias"] as $categoria) {
					$ordem = ordemAtual("noticias", array("categorias"=>$categoria, "status"=>"1"), true, $dadosJSON["noticias"]["id"]);
					$categorias[$categoria]=$ordem;
				}
				$dados["resultados"]["visivel"]=$dadosJSON["visivel"];
				if (!empty($categorias)) {
					$dados["resultados"]["categorias"]=serialize($categorias);
				} else {
					$dados["resultados"]["categorias"]="";
					$dados["resultados"]["visivel"]="0";
				}
				dbUpdate("noticias", $dados);
	
				//Gera o retorno do JSON
				$json['status'] = 1;
				$json['mensagem']= "Dados salvos com sucesso! ";
			}
		}
		//Carrega o ID do Módulo
		$moduloMetatag=$modelSite->idModulo("metatags_ack",true);

		$modelUser=new ACKuser_Model();
		if($modelUser->userLevel($moduloMetatag,false,"2") and $dadosJSON["acao"]=="editar") {
			// Pega o ID da Metatag
			$modelSite=new ACKsite_Model();
			$idMT=$modelSite->metaTagsSite("noticias",$dadosJSON["noticias"]["id"]);
			if (!$idMT) {
				$dadosMetaTag["resultados"]["tabela"]="noticias";
				$dadosMetaTag["resultados"]["item"]=$dadosJSON["noticias"]["id"];
				$idMetatag=dbSave("metatags",$dadosMetaTag,true);
			} else {
				$idMetatag=$idMT["id"];
			}
			
			// Salva a Metatag
			if ($dadosJSON["metaTags"]["title"] or $dadosJSON["metaTags"]["description"] or $dadosJSON["metaTags"]["keywords"]) {
				$dadosMetaTag["resultados"]=$dadosJSON["metaTags"];
				$dadosMetaTag["resultados"]["id"]=$idMetatag;
				dbUpdate("metatags", $dadosMetaTag);
			}
			
			//Gera o retorno do JSON
			$json['status'] = 1;
			$json['mensagem'].= "Metatags salvas com sucesso! ";
		}
		echo newJSON($json);
	}
	###############################################################################
	## CATEGORIAS
	###############################################################################
    function categorias($dadosURL) {
		$acao=$dadosURL[0];
		if ($acao!="") {
			$funcao=$acao."Categoria";
			$this->$funcao($dadosURL);
		} else {
			protectedArea();
			openSession();
			verifyTimeSession();
			$dados=array();
			//Pega os dados do Site
			$modelSite=new ACKsite_Model();
			$dadosSite=$modelSite->dadosSite();
			$dados["dadosSite"]=$dadosSite;

			//Carrega o ID do Módulo
			$moduloVerifica=$modelSite->idModulo("categorias_noticias",true);
	
			//Pega os dados do Usuário
			$modelUser=new ACKuser_Model();
			$modelUser->userLevel($moduloVerifica,true);	
			$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));
			$dados["dragDrop"]=true;

			$dados["tituloPagina"]="Categorias de notícias";
			loadView("ack/noticias_categorias",$dados);
		}
	}
	function incluirCategoria() {
		protectedArea();
		openSession();
		verifyTimeSession();
		$dados=array();
		
		//Pega os dados do Site
		$modelSite=new ACKsite_Model();
		$dadosSite=$modelSite->dadosSite();
		$dados["dadosSite"]=$dadosSite;
		$dados["conteudoIdiomas"]=$modelSite->idiomasSite();

		//Carrega o ID do Módulo
		$moduloVerifica=$modelSite->idModulo("categorias_noticias",true);
		$moduloNoticias=$modelSite->idModulo("noticias",false);

		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true,"2");	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));

		//Variáveis que controlam itens do layout
		$dados["tituloPagina"]="Incluir categoria de notícias";
		$dados["tipoAcao"]="incluir";
		$dados["plugins"]=true;
		$dados["multiplasImagens"]=true; //True se puder enviar várias imagens, false se puder enviar apenas uma
		$dados["tamanhoCrop"]="500 400"; //String com a largura (espaço) altura do crop, ou false se não houver crop
		$dados["abaImagens"]=true; //True se aba imagens deve estar visível ou false se não deve
		$dados["abaVideos"]=true; //True se aba imagens deve estar visível ou false se não deve
		$dados["abaAnexos"]=true; //True se aba imagens deve estar visível ou false se não deve
		
		//Função para listar as categorias
		function listaCategoria($modulo,$subcategoria,&$arrayInt,$nivel) {
			$modelGeral=new ACKgeral_Model();
			$resultados=$modelGeral->listaCategorias($modulo,$subcategoria);
			if ($resultados) {
				foreach ($resultados as $resultado) {
					array_push($arrayInt, array('id'=>$resultado["id"], 'nome'=>$resultado["titulo_pt"], 'nivel'=>$nivel));
					listaCategoria($modulo,$resultado["id"],$arrayInt,$nivel+1);
				}
			}
		}
		$arrayCategoria=array();
		array_push($arrayCategoria, array('id'=>"0", 'nome'=>"Nível principal", 'nivel'=>"0"));
		listaCategoria($moduloNoticias,"0",$arrayCategoria,0);
		$dados["categoriasSelect"]=$arrayCategoria;

		//Nível de permissão das Metatags
		$moduloVerificaMetatags=$modelSite->idModulo("metatags_ack",true);
		$dados["nivelMetatags"]=$modelUser->userLevel($moduloVerificaMetatags,false, false);
		$dados["nivelPermissao"]="2";		
		
		//Carrega o layout do site
		loadView("ack/noticias_categoria",$dados);
	}
	function editarCategoria($dadosURL) {
		$id=$dadosURL[1];
		protectedArea();
		openSession();
		verifyTimeSession();
		$dados=array();
		
		//Pega os dados do Site
		$modelSite=new ACKsite_Model();
		$dadosSite=$modelSite->dadosSite();
		$dados["dadosSite"]=$dadosSite;
		$dados["conteudoIdiomas"]=$modelSite->conteudoIdioma("categorias",$id,"titulo");

		//Carrega o ID do Módulo
		$moduloVerifica=$modelSite->idModulo("categorias_noticias",true);
		$moduloNoticias=$modelSite->idModulo("noticias",false);

		//Pega os dados do Usuário
		$modelUser=new ACKuser_Model();
		$modelUser->userLevel($moduloVerifica,true,"1");	
		$dados["dadosUserHeader"]=$modelUser->dataUser(array("email"=>$_SESSION["email"]));
		
		//Pega os dados da Categoria
		$modelGeral=new ACKgeral_Model();
		$dados["dadosCategoria"]=$modelGeral->dataCategoria(array("id"=>$id));
		if (!$dados["dadosCategoria"]) {
			$dadosErro["erro"]=array("titulo"=>"CATEGORIA NÃO EXISTE","conteudo"=>"A categoria que você tentou acessar não existe ou foi excluída.","linkACK"=>true);
			loadView("__erro",$dadosErro);
			exit;
		}
		
		//Pega os dados das Metatags
		$dados["metatags"]=$modelSite->metaTagsSite("categorias",$id);
		
		//Variáveis que controlam itens do layout
		$dados["tituloPagina"]="Editar categoria de notícias";
		$dados["tipoAcao"]="editar";
		$dados["plugins"]=true;
		$dados["multiplasImagens"]=true; //True se puder enviar várias imagens, false se puder enviar apenas uma
		$dados["tamanhoCrop"]="500 400"; //String com a largura (espaço) altura do crop, ou false se não houver crop
		$dados["abaImagens"]=true; //True se aba imagens deve estar visível ou false se não deve
		$dados["abaVideos"]=true; //True se aba imagens deve estar visível ou false se não deve
		$dados["abaAnexos"]=true; //True se aba imagens deve estar visível ou false se não deve
		
		//Função para listar as categorias
		function listaCategoria($modulo,$subcategoria,&$arrayInt,$nivel) {
			$modelGeral=new ACKgeral_Model();
			$resultados=$modelGeral->listaCategorias($modulo,$subcategoria);
			if ($resultados) {
				foreach ($resultados as $resultado) {
					array_push($arrayInt, array('id'=>$resultado["id"], 'nome'=>$resultado["titulo_pt"], 'nivel'=>$nivel));
					listaCategoria($modulo,$resultado["id"],$arrayInt,$nivel+1);
				}
			}
		}
		$arrayCategoria=array();
		array_push($arrayCategoria, array('id'=>"0", 'nome'=>"Nível principal", 'nivel'=>"0"));
		listaCategoria($moduloNoticias,"0",$arrayCategoria,0);
		$dados["categoriasSelect"]=$arrayCategoria;

		//Libera o acesso para edição do conteúdo, ou apenas visualização
		$nivelPermissao=$modelUser->userLevel($moduloVerifica,false);
		$dados["nivelPermissao"]=$nivelPermissao["nivel"];

		//Nível de permissão das Metatags
		$moduloVerificaMetatags=$modelSite->idModulo("metatags_ack",true);
		$dados["nivelMetatags"]=$modelUser->userLevel($moduloVerificaMetatags,false, false);
		
		//Carrega o layout do site
		loadView("ack/noticias_categoria",$dados);
	}
	function salvarCategoria() {
		postRequest();
		$dadosJSON=readJSON($_POST["ajaxACK"]);
		protectedArea();
		openSession();
		verifyTimeSession();
		
		//Carrega o ID do Módulo
		$modelSite=new ACKsite_Model();
		$moduloVerifica=$modelSite->idModulo("categorias_noticias",true);
		$moduloNoticias=$modelSite->idModulo("noticias",false);
		
		$modelUser=new ACKuser_Model();
		if (!$modelUser->userLevel($moduloVerifica,false,"2")) {
			$json['status'] = 0;
			$json['mensagem'] = "Usuário sem permissão para salvar categoria. ";
		} else {
			if ($dadosJSON["acao"]=="incluir") {
				//Salva o Tópico
				$dados["resultados"]=$dadosJSON["categorias"];
				$dados["resultados"]["visivel"]=$dadosJSON["visivel"];
				$dados["resultados"]["modulo"]=$moduloNoticias;
				$dados["resultados"]["status"]="1";
				$ordem = proximaOrdem("categorias", array("modulo"=>$moduloNoticias, "relacao_id"=>$dadosJSON["categorias"]["relacao_id"], "status"=>"1"));
				$dados["resultados"]["ordem"]=$ordem;
				$idItem=dbSave("categorias",$dados,true);
				
				//Cria a Meta-tag
				$dadosMetaTag["resultados"]["tabela"]="categorias";
				$dadosMetaTag["resultados"]["item"]=$idItem;
				dbSave("metatags",$dadosMetaTag);
				
				//Gera os retornos do JSON
				$json['status'] = 1;
				$json['mensagem']= "Dados salvos com sucesso! ";
				$json['id'] = $idItem;
			} elseif ($dadosJSON["acao"]=="editar") {
				// Salva o tópico
				$dados["resultados"]=$dadosJSON["categorias"];
				$dados["resultados"]["visivel"]=$dadosJSON["visivel"];
				if ($dadosJSON["categorias"]["relacao_idAtual"]!=$dadosJSON["categorias"]["relacao_id"]) {
					$dados["resultados"]["ordem"] = proximaOrdem("categorias", array("modulo"=>$moduloNoticias, "relacao_id"=>$dadosJSON["categorias"]["relacao_id"], "status"=>"1"));
				}
				unset($dados["resultados"]["relacao_idAtual"]);
				dbUpdate("categorias", $dados);
					
				//Gera o retorno do JSON
				$json['status'] = 1;
				$json['mensagem']= "Dados salvos com sucesso! ";
			}
		}
		//Carrega o ID do Módulo
		$moduloMetatag=$modelSite->idModulo("metatags_ack",true);

		$modelUser=new ACKuser_Model();
		if($modelUser->userLevel($moduloMetatag,false,"2") and $dadosJSON["acao"]=="editar") {
			// Pega o ID da Metatag
			$modelSite=new ACKsite_Model();
			$idMT=$modelSite->metaTagsSite("categorias",$dadosJSON["categorias"]["id"]);
			if (!$idMT) {
				$dadosIDMetaTag["resultados"]["tabela"]="categorias";
				$dadosIDMetaTag["resultados"]["item"]=$dadosJSON["categorias"]["id"];
				$idMetatag=dbSave("metatags",$dadosIDMetaTag,true);
			} else {
				$idMetatag=$idMT["id"];
			}
			
			// Salva a Metatag
			$dadosMetaTag["resultados"]=$dadosJSON["metaTags"];
			$dadosMetaTag["resultados"]["id"]=$idMetatag;
			dbUpdate("metatags", $dadosMetaTag);
			
			//Gera o retorno do JSON
			$json['status'] = 1;
			$json['mensagem'].= "Metatags salvas com sucesso! ";
		}
		echo newJSON($json);
	}
	function excluirCategoria($dadosJSON) {
		postRequest();
		$dadosJSON=readJSON($_POST["ajaxACK"]);
		protectedArea();
		openSession();
		verifyTimeSession();
		$itens_lista=explode(",",$dadosJSON["itens_lista"]);
		
		//Carrega o ID do Módulo
		$modelSite=new ACKsite_Model();
		$moduloVerifica=$modelSite->idModulo("categorias_noticias",true);
		
		$modelUser=new ACKuser_Model();
		if(!$modelUser->userLevel($moduloVerifica, false, "2")) {
			$json['status'] = 0;
			$json['mensagem'] = "Usuário sem permissão.";
		} else {
			//Exclui as categorias e itens dentro delas
			$total=0;
			$json["array"]=array();
			foreach ($itens_lista as $item) {
				//Exclui os itens daquela categoria
				$modelGeral=new ACKgeral_Model();
				$noticias = $modelGeral->listaItens("noticias",0,999999999999,false,$item);
				$json[$item]="categoria";
				$json['noticias']=$noticias;
				foreach ($noticias as $noticia) {
					$categoriasNoticia=unserialize($noticia["categorias"]);
					unset ($categoriasNoticia[$item]);
					if (empty($categoriasNoticia)) {
						$dadosNoticia["resultados"]["id"]=$produto["id"];
						$dadosNoticia["resultados"]["categorias"]="";
						$dadosNoticia["resultados"]["visivel"]="0";
						dbUpdate("noticias", $dadosNoticia);
					} else {
						$dadosNoticia["resultados"]["id"]=$produto["id"];
						$dadosNoticia["resultados"]["categorias"]=serialize($categoriasNoticia);
						dbUpdate("noticias", $dadosNoticia);
					}
				}
							
				//Exclui a categoria
				dbDelete("categorias", $item);
				array_push($json["array"], $item);
				$total++;
			}
			$json['status']=1;
			$json["total"]=$total;
		}
		echo newJSON($json);
	}
}
?>