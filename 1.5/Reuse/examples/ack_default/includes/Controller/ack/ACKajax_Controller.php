<?php
class ACKajax_Controller
{
    function index () {
		postRequest();
		$dadosJson=readJSON($_POST["ajaxACK"]);
		$acao=$dadosJson["acao"];
		$this->$acao($dadosJson);
	}
    function login ($dadosUser) {
		postRequest();
		$model=new ACKuser_Model();
		$userPass=array("usuario"=>$dadosUser["usuario"], "senha"=>md5($dadosUser["senha"]));
		$logado=$model->verifyUser($userPass);
		if ($logado) {
			global $nome_sessao;
			global $endereco_site;
			global $tempo_sessao;
			if ($dadosUser["lembrar"]=="1") {
				newCookie("rU", $dadosUser["usuario"]);
				newCookie("rS", base64_encode(md5($dadosUser["senha"])));
			}
			$dados=array("resultados"=>array("email"=>$logado["email"], "id"=>$logado["id"], "nome"=>$logado["nome"], "ultimo_acesso"=>$logado["ultimo_acesso"], "expire"=>time()+$tempo_sessao));
			newSession($nome_sessao."ACK", $dados);
			$model->updateLastAccess();
			$json=array('status'=>1, 'mensagem'=>'Login efetuado com sucesso', 'url'=>$endereco_site."/ack/dashboard");
		} else {
			global $nome_sessao;
			$json=array('status'=>0, 'mensagem'=>'Usuário ou senha incorretos');
			closeSession($nome_sessao."ACK");
			delCookie("rU");
			delCookie("rS");
		}
		echo newJSON($json);
	}
    function sair () {
		global $nome_sessao;
		global $endereco_site;
		closeSession($nome_sessao);
		delCookie("rU");
		delCookie("rS");
		header('Location:'.$endereco_site."/ack");
	}
	function rec_senha ($dadosUser) {
		postRequest();
		global $endereco_site;
		$model=new ACKuser_Model();
		$usuario=array("email"=>$dadosUser["email"]);
		$existe=$model->verifyEmail($usuario);
		// Verifica se o usuário existe. Se existe faz o envio de e-mail e troca no banco, caso contrário retorna um erro
		if ($existe) {
			//Gera nova Senha
			$novaSenha=geraSenha();
			
			//Adiciona mais itens em comum para o array de variáveis do e-mail
			$existe["nova_senha"]=$novaSenha;
			$existe["assunto"]="Recuperação de Senha";
			
			//Verifica o envio do e-mail. Se for ok, altera a senha no banco de dados, se não for, retorna um erro.
			if (enviaEmail($dadosUser["email"], "ACKrec_senha", $existe)) {
				dbUpdate("usuarios", array("resultados"=>array("senha"=>md5($novaSenha), "id"=>$existe["id"])),false);
				$json=array('status'=>1, 'mensagem'=>'Nova senha enviada com sucesso');
			} else {
				$json=array('status'=>0, 'mensagem'=>'Erro no envio do e-mail. A senha não foi alterada');
			}
		} else {
			$json=array('status'=>0, 'mensagem'=>'O usuário não existe');
		}
		echo newJSON($json);
	}
	function permissoes ($dadosJSON) {
		postRequest();
		protectedArea();
		openSession();
		verifyTimeSession();

		//Carrega o ID do Módulo
		$modelSite=new ACKsite_Model();
		$moduloVerifica=$modelSite->idModulo("usuarios_ack",true);

		$usuario=$dadosJSON["usuario"];
		$modelUser=new ACKuser_Model();	
		$permissao=$modelUser->userLevel($moduloVerifica, false, "2");
		if (!$modelUser->userLevel($moduloVerifica, false)) {
			$json=array('status'=>0, 'mensagem'=>'Usuário sem Permissão');
			echo newJSON($json);
			exit;
		}

		if (!$permissao) {
			$nivel="disabled=\"disabled\"";
		} else {
			$nivel="";
		}
		if (!$usuario or $usuario=="") {
			$usuario=$_SESSION["id"];
		}
		// Executa o comando no banco de dados
		global $db;
		$mysql = $db->prepare('SELECT id,titulo_pt FROM modulos WHERE status="1";');
		@$mysql->execute();
		$modulos = $mysql->fetchAll();
		if (!$modulos or count($modulos)=="0") {
			$json=array('status'=>0, 'mensagem'=>'Nenhum módulo cadastrado');
		} else {
			$json=array();
			foreach ($modulos as $modulo) {
				$mysql = $db->prepare('SELECT id,nivel FROM permissoes WHERE usuario="'.$usuario.'" AND modulo="'.$modulo["id"].'"');
				@$mysql->execute();
				$permissao = $mysql->fetchAll();
				if (count($permissao)>=1) {
					$json[$permissao[0]["id"]]=array($modulo["titulo_pt"],$permissao[0]["nivel"],$nivel);
				} else {
					$novaPerm=dbSave("permissoes", array("resultados"=>array("modulo"=>$modulo["id"], "usuario"=>$usuario, "nivel"=>"0")),true, false);
					$json[$novaPerm]=array($modulo["titulo_pt"],"0",$nivel);
				}
			}
		}
		echo newJSON($json);
	}
	function carregar_mais($dadosJSON) {		
		postRequest();
		protectedArea();
		openSession();
		verifyTimeSession();

		$modulo=$dadosJSON["modulo"];
		$itens=$dadosJSON["qtd_itens"];
		$arr['parent_lista'] = $modulo;
		
		switch($modulo){
			case 'destaques':
				$modelGeral=new ACKgeral_Model();
			
				$modelSite=new ACKsite_Model();
				$dadosSite=$modelSite->dadosSite();
				(int)$limite=$dadosSite["itens_pagina"];
			
				$destaques=$modelGeral->listaDestaques($itens,$limite);
				if (count($destaques) <= $limite){
					$arr['exibir_botao'] = 0;
				} else {
					$arr['exibir_botao'] = 1;
				}
				$u=1;
				$arr['grupo']=array();
				foreach ($destaques as $destaque) {
					if ($u<=$limite) {
						if ($destaque["modulo"]=="0") {
							$nomeModulo="Página Inicial";
						} else {
							$dadosModulo=$modelGeral->dataModulo($destaque["modulo"]);
							$nomeModulo=$dadosModulo["titulo_pt"];
						}
						array_push($arr['grupo'],array('id'=>$destaque["id"],'nome'=>$destaque["titulo_pt"],'url'=>$destaque["url_pt"],'relacao'=>$nomeModulo,'ordem'=>$destaque["ordem"],'visivel'=>$destaque["visivel"]));
						$u++;
					}
				}				
				echo newJSON($arr);
			break;

			case 'enderecos':
				$modelSite=new ACKsite_Model();
				$dadosSite=$modelSite->dadosSite();
				(int)$limite=$dadosSite["itens_pagina"];
			
				$enderecos=$modelSite->listaEnderecos($itens,$limite);
				if (count($enderecos) <= $limite){
					$arr['exibir_botao'] = 0;
				} else {
					$arr['exibir_botao'] = 1;
				}
				$u=1;
				$arr['grupo']=array();
				foreach ($enderecos as $endereco) {
					if ($u<=$limite) {
						array_push($arr['grupo'],array('id'=>$endereco["id"],'nome'=>$endereco["nome_pt"],'email'=>$endereco["email_pt"],'ordem'=>$endereco["ordem"],'visivel'=>$endereco["visivel"]));
						$u++;
					}
				}				
				echo newJSON($arr);
			break;

			case 'usuarios':
				$modelSite=new ACKsite_Model();
				$dadosSite=$modelSite->dadosSite();
				(int)$limite=$dadosSite["itens_pagina"];
			
				$modelUser=new ACKuser_Model();
				$usuarios=$modelUser->listaUsuarios($itens,$limite);
				if (count($usuarios) <= $limite){
					$arr['exibir_botao'] = 0;
				} else {
					$arr['exibir_botao'] = 1;
				}
				$u=1;
				$arr['grupo']=array();
				foreach ($usuarios as $usuario) {
					if ($u<=$limite) {
						array_push($arr['grupo'],array('id'=>$usuario["id"],'nome'=>$usuario["nome"],'email'=>$usuario["email"],'dt_inc'=>convertDate($usuario["dt_inc"], "%d-%m-%Y")));
						$u++;
					}
				}				
				echo newJSON($arr);
			break;
			
			case 'modulos':
				//Pega os dados do Site
				$modelSite=new ACKsite_Model();
				$dadosSite=$modelSite->dadosSite();
				(int)$limite=$dadosSite["itens_pagina"];
				$idioma=$modelSite->idiomasSite("1");

				//Chama o Model Geral para listar os tópicos
				$modelGeral=new ACKgeral_Model();
				$modulos=$modelGeral->listaModulos($itens,$limite);
				if (count($modulos) <= $limite){
					$arr['exibir_botao'] = 0;
				} else {
					$arr['exibir_botao'] = 1;
				}				
				$arr['grupo']=array();
				$u=1;			
				foreach ($modulos as $modulo) {
					if ($u<=$limite) {
						array_push($arr['grupo'],array('id'=>$modulo["id"],'titulo'=>$modulo["titulo_".$idioma[0]["abreviatura"]]));
						$u++;
					}
				}			
				echo newJSON($arr);
			break;
						
			case 'topicos':
				//Pega os dados do Site
				$modelSite=new ACKsite_Model();
				$dadosSite=$modelSite->dadosSite();
				(int)$limite=$dadosSite["itens_pagina"];
				$idioma=$modelSite->idiomasSite("1");

				//Chama o Model Geral para listar os tópicos
				$modelGeral=new ACKgeral_Model();
				$topicos=$modelGeral->listaTopicos($itens,$limite);
				if (count($topicos) <= $limite){
					$arr['exibir_botao'] = 0;
				} else {
					$arr['exibir_botao'] = 1;
				}				
				$arr['grupo']=array();
				$u=1;			
				foreach ($topicos as $topico) {
					if ($u<=$limite) {
						array_push($arr['grupo'],array('id'=>$topico["id"],'titulo'=>$topico["titulo_".$idioma[0]["abreviatura"]],'ordemTopico'=>$topico["ordem"],'visivel'=>$topico["visivel"]));
						$u++;
					}
				}			
				echo newJSON($arr);
			break;

			case 'contatos':
				//Pega os dados do Site
				$modelSite=new ACKsite_Model();
				$dadosSite=$modelSite->dadosSite();
				(int)$limite=$dadosSite["itens_pagina"];
				$idioma=$modelSite->idiomasSite("1");

				//Chama o Model Geral para listar os tópicos
				$modelGeral=new ACKgeral_Model();
				$contatos=$modelGeral->listaContatos($itens,$limite);
				if (count($contatos) <= $limite){
					$arr['exibir_botao'] = 0;
				} else {
					$arr['exibir_botao'] = 1;
				}				
				$arr['grupo']=array();
				$u=1;			
				foreach ($contatos as $contato) {
					if ($u<=$limite) {
						$setor=retornaSetor($contato["setor"]);
						$recebido=convertDate($contato["data"], "%d-%m-%Y");
						array_push($arr['grupo'],array('id'=>$contato["id"],'setor'=>$setor,'recebido'=>$recebido,'remetente'=>$contato["remetente"],'email'=>$contato["email"],'marcar'=>$contato["lido"]));
						$u++;
					}
				}			
				echo newJSON($arr);
			break;
						
			case 'produtos':
				if ($dadosJSON["categoria"]=="") {
					$arr['parent_lista'] = "semCategoria";
				} else {
					$arr['parent_lista'] = $dadosJSON["categoria"];
				}
				
				if ($dadosJSON["categoria"]=="0") {
					$categoriaProduto=false;
				} elseif ($dadosJSON["categoria"]=="") {
					$categoriaProduto="semCategoria";
				} else {
					$categoriaProduto=$dadosJSON["categoria"];
				}
			
				//Pega os dados do Site
				$modelSite=new ACKsite_Model();
				$dadosSite=$modelSite->dadosSite();
				(int)$limite=$dadosSite["itens_pagina"];
				$idioma=$modelSite->idiomasSite("1");

				//Chama o Model Geral para listar os tópicos
				$modelGeral=new ACKgeral_Model();
				$produtos=$modelGeral->listaItens("produtos", $itens,$limite,true,$categoriaProduto);
				if (count($produtos) <= $limite){
					$arr['exibir_botao'] = 0;
				} else {
					$arr['exibir_botao'] = 1;
				}				
				$arr['grupo']=array();
				$p=1;			
				foreach ($produtos as $produto) {
					if ($p<=$limite) {
						$ordem=$modelGeral->ordemItem("produtos", $produto["id"], $categoriaProduto);
						array_push($arr['grupo'],array('id'=>$produto["id"],'nomeProduto'=>$produto["titulo_".$idioma[0]["abreviatura"]],'ordem'=>$ordem,'visivel'=>$produto["visivel"]));
						$p++;
					}
				}			
				echo newJSON($arr);
			break;

			case 'servicos':
				if ($dadosJSON["categoria"]=="") {
					$arr['parent_lista'] = "semCategoria";
				} else {
					$arr['parent_lista'] = $dadosJSON["categoria"];
				}
				
				if ($dadosJSON["categoria"]=="0") {
					$categoriaServico=false;
				} elseif ($dadosJSON["categoria"]=="") {
					$categoriaServico="semCategoria";
				} else {
					$categoriaServico=$dadosJSON["categoria"];
				}
			
				//Pega os dados do Site
				$modelSite=new ACKsite_Model();
				$dadosSite=$modelSite->dadosSite();
				(int)$limite=$dadosSite["itens_pagina"];
				$idioma=$modelSite->idiomasSite("1");

				//Chama o Model Geral para listar os tópicos
				$modelGeral=new ACKgeral_Model();
				$servicos=$modelGeral->listaItens("servicos", $itens,$limite,true,$categoriaServico);
				if (count($servicos) <= $limite){
					$arr['exibir_botao'] = 0;
				} else {
					$arr['exibir_botao'] = 1;
				}				
				$arr['grupo']=array();
				$p=1;			
				foreach ($servicos as $servico) {
					if ($p<=$limite) {
						$ordem=$modelGeral->ordemItem("servicos", $produto["id"], $categoriaServico);
						array_push($arr['grupo'],array('id'=>$servico["id"],'nomeServico'=>$servico["titulo_".$idioma[0]["abreviatura"]],'ordem'=>$ordem,'visivel'=>$servico["visivel"]));
						$p++;
					}
				}			
				echo newJSON($arr);
			break;

			case 'noticias':
				if ($dadosJSON["categoria"]=="") {
					$arr['parent_lista'] = "semCategoria";
				} else {
					$arr['parent_lista'] = $dadosJSON["categoria"];
				}
				
				if ($dadosJSON["categoria"]=="0") {
					$categoriaNoticia=false;
				} elseif ($dadosJSON["categoria"]=="") {
					$categoriaNoticia="semCategoria";
				} else {
					$categoriaNoticia=$dadosJSON["categoria"];
				}
			
				//Pega os dados do Site
				$modelSite=new ACKsite_Model();
				$dadosSite=$modelSite->dadosSite();
				(int)$limite=$dadosSite["itens_pagina"];
				$idioma=$modelSite->idiomasSite("1");

				//Chama o Model Geral para listar os tópicos
				$modelGeral=new ACKgeral_Model();
				$noticias=$modelGeral->listaItens("noticias", $itens,$limite,true,$categoriaNoticia,"data DESC");
				if (count($noticias) <= $limite){
					$arr['exibir_botao'] = 0;
				} else {
					$arr['exibir_botao'] = 1;
				}				
				$arr['grupo']=array();
				$p=1;			
				foreach ($noticias as $noticia) {
					if ($p<=$limite) {
						$data=convertDate($noticia["data"], "%d-%m-%Y");
						array_push($arr['grupo'],array('id'=>$noticia["id"],'nomeProduto'=>$noticia["titulo_".$idioma[0]["abreviatura"]],'data'=>$data,'visivel'=>$noticia["visivel"]));
						$p++;
					}
				}			
				echo newJSON($arr);
			break;

			case 'imprensa':
				if ($dadosJSON["categoria"]=="") {
					$arr['parent_lista'] = "semCategoria";
				} else {
					$arr['parent_lista'] = $dadosJSON["categoria"];
				}
				
				if ($dadosJSON["categoria"]=="0") {
					$categoriaImprensa=false;
				} elseif ($dadosJSON["categoria"]=="") {
					$categoriaImprensa="semCategoria";
				} else {
					$categoriaImprensa=$dadosJSON["categoria"];
				}
			
				//Pega os dados do Site
				$modelSite=new ACKsite_Model();
				$dadosSite=$modelSite->dadosSite();
				(int)$limite=$dadosSite["itens_pagina"];
				$idioma=$modelSite->idiomasSite("1");

				//Chama o Model Geral para listar os tópicos
				$modelGeral=new ACKgeral_Model();
				$noticias=$modelGeral->listaItens("imprensa", $itens,$limite,true,$categoriaImprensa,"data DESC");
				if (count($noticias) <= $limite){
					$arr['exibir_botao'] = 0;
				} else {
					$arr['exibir_botao'] = 1;
				}				
				$arr['grupo']=array();
				$p=1;			
				foreach ($noticias as $noticia) {
					if ($p<=$limite) {
						$data=convertDate($noticia["data"], "%d-%m-%Y");
						array_push($arr['grupo'],array('id'=>$noticia["id"],'titulo'=>$noticia["titulo_".$idioma[0]["abreviatura"]],'data'=>$data,'visivel'=>$noticia["visivel"]));
						$p++;
					}
				}			
				echo newJSON($arr);
			break;

			case 'categoriasProdutos':
				//Pega o limite
				$modelSite=new ACKsite_Model();
				$dadosSite=$modelSite->dadosSite();
				$limite=$dadosSite["itens_pagina"];
				$arr['parent_lista'] = "categorias";

				//Carrega o ID do Módulo
				$moduloProdutos=$modelSite->idModulo("produtos",false);

				//Função para listar as categorias
				function listaCategoria($modulo,$subcategoria,&$arrayInt) {
					$modelGeral=new ACKgeral_Model();
					$resultados=$modelGeral->listaCategorias($modulo,$subcategoria);
					if ($resultados) {
						foreach ($resultados as $resultado) {
							array_push($arrayInt, array('id'=>$resultado["id"], 'categoria'=>$resultado["titulo_pt"], 'ordem'=>$resultado["ordem"], 'visivel'=>$resultado["visivel"], 'relacao'=>$resultado["relacao_id"]));
							listaCategoria($modulo,$resultado["id"],$arrayInt);
						}
					}
				}
				$arrayCategoria=array();
				listaCategoria($moduloProdutos,"0",$arrayCategoria);
				$totalItens=count($arrayCategoria);
				if ($totalItens <= $itens+$limite) {
					$arr['exibir_botao'] = 0;
				} else {
					$arr['exibir_botao'] = 1;
				}
				$arr["grupo"]=array_slice($arrayCategoria, $itens, $limite);
				echo newJSON($arr);
			break;

			case 'categoriasServicos':
				//Pega o limite
				$modelSite=new ACKsite_Model();
				$dadosSite=$modelSite->dadosSite();
				$limite=$dadosSite["itens_pagina"];
				$arr['parent_lista'] = "categorias";

				//Carrega o ID do Módulo
				$moduloServicos=$modelSite->idModulo("servicos",false);

				//Função para listar as categorias
				function listaCategoria($modulo,$subcategoria,&$arrayInt) {
					$modelGeral=new ACKgeral_Model();
					$resultados=$modelGeral->listaCategorias($modulo,$subcategoria);
					if ($resultados) {
						foreach ($resultados as $resultado) {
							array_push($arrayInt, array('id'=>$resultado["id"], 'categoria'=>$resultado["titulo_pt"], 'ordem'=>$resultado["ordem"], 'visivel'=>$resultado["visivel"], 'relacao'=>$resultado["relacao_id"]));
							listaCategoria($modulo,$resultado["id"],$arrayInt);
						}
					}
				}
				$arrayCategoria=array();
				listaCategoria($moduloServicos,"0",$arrayCategoria);
				$totalItens=count($arrayCategoria);
				if ($totalItens <= $itens+$limite) {
					$arr['exibir_botao'] = 0;
				} else {
					$arr['exibir_botao'] = 1;
				}
				$arr["grupo"]=array_slice($arrayCategoria, $itens, $limite);
				echo newJSON($arr);
			break;

			case 'categoriasNoticias':
				//Pega o limite
				$modelSite=new ACKsite_Model();
				$dadosSite=$modelSite->dadosSite();
				$limite=$dadosSite["itens_pagina"];
				$arr['parent_lista'] = "categorias";

				//Carrega o ID do Módulo
				$moduloNoticias=$modelSite->idModulo("noticias",false);

				//Função para listar as categorias
				function listaCategoria($modulo,$subcategoria,&$arrayInt) {
					$modelGeral=new ACKgeral_Model();
					$resultados=$modelGeral->listaCategorias($modulo,$subcategoria);
					if ($resultados) {
						foreach ($resultados as $resultado) {
							array_push($arrayInt, array('id'=>$resultado["id"], 'categoria'=>$resultado["titulo_pt"], 'ordem'=>$resultado["ordem"], 'visivel'=>$resultado["visivel"], 'relacao'=>$resultado["relacao_id"]));
							listaCategoria($modulo,$resultado["id"],$arrayInt);
						}
					}
				}
				$arrayCategoria=array();
				listaCategoria($moduloNoticias,"0",$arrayCategoria);
				$totalItens=count($arrayCategoria);
				if ($totalItens <= $itens+$limite) {
					$arr['exibir_botao'] = 0;
				} else {
					$arr['exibir_botao'] = 1;
				}
				$arr["grupo"]=array_slice($arrayCategoria, $itens, $limite);
				echo newJSON($arr);
			break;
			
			case 'categoriasImprensa':
				//Pega o limite
				$modelSite=new ACKsite_Model();
				$dadosSite=$modelSite->dadosSite();
				$limite=$dadosSite["itens_pagina"];
				$arr['parent_lista'] = "categorias";

				//Carrega o ID do Módulo
				$moduloImprensa=$modelSite->idModulo("imprensa",false);

				//Função para listar as categorias
				function listaCategoria($modulo,$subcategoria,&$arrayInt) {
					$modelGeral=new ACKgeral_Model();
					$resultados=$modelGeral->listaCategorias($modulo,$subcategoria);
					if ($resultados) {
						foreach ($resultados as $resultado) {
							array_push($arrayInt, array('id'=>$resultado["id"], 'categoria'=>$resultado["titulo_pt"], 'ordem'=>$resultado["ordem"], 'visivel'=>$resultado["visivel"], 'relacao'=>$resultado["relacao_id"]));
							listaCategoria($modulo,$resultado["id"],$arrayInt);
						}
					}
				}
				$arrayCategoria=array();
				listaCategoria($moduloImprensa,"0",$arrayCategoria);
				$totalItens=count($arrayCategoria);
				if ($totalItens <= $itens+$limite) {
					$arr['exibir_botao'] = 0;
				} else {
					$arr['exibir_botao'] = 1;
				}
				$arr["grupo"]=array_slice($arrayCategoria, $itens, $limite);
				echo newJSON($arr);
			break;

			case 'lista_LOG':
				$userLog=$dadosJSON["filtroLog"]["log_usuario"];
				if ($userLog!="") {
					$where["usuario"]=$userLog;
				}		
				$periodo=$dadosJSON["filtroLog"]["log_periodo"];
				if ($periodo!="") {
					$dataLog=subDays(date("Ymd"),$periodo);
					$where["periodo"]=$dataLog;	
				}
				$modelSite=new ACKsite_Model();
				$dadosSite=$modelSite->dadosSite();
				(int)$limite=$dadosSite["itens_pagina"];
			
				$modelLogs=new ACKlogs_Model();
				$logs=$modelLogs->listaLogs($itens,$limite,$where);
				if (count($logs) <= $limite){
					$arr['exibir_botao'] = 0;
				} else {
					$arr['exibir_botao'] = 1;
				}
				$u=1;
				$arr['grupo']=array();
				foreach ($logs as $log) {
					if ($u<=$limite) {
						array_push($arr['grupo'],array('orIDlog'=>str_pad($log["id"], 6, "0", STR_PAD_LEFT),'dataLog'=>convertDate($log["data"], "%d-%m-%Y às %Hh%Mm%Ss"),'orMensagem'=>$log["texto_log"]));
						$u++;
					}
				}				
				echo newJSON($arr);
			break;
			
			case 'newsletter':
				//Pega os dados do Site
				$modelSite=new ACKsite_Model();
				$dadosSite=$modelSite->dadosSite();
				(int)$limite=$dadosSite["itens_pagina"];

				//Chama o Model Geral para listar os cadastros
				$modelGeral=new ACKgeral_Model();
				$newsletters=$modelGeral->listaNewsletter($itens,$limite);

				if (count($newsletters) <= $limite){
					$arr['exibir_botao'] = 0;
				} else {
					$arr['exibir_botao'] = 1;
				}				
				$arr['grupo']=array();
				$u=1;	
				foreach ($newsletters as $newsletter) {
					if ($u<=$limite) {
						if ($newsletter["sexo"]=="m") {
							$sexo="Masculino";
						} else {
							$sexo="Feminino";
						}
						array_push($arr['grupo'],array('id'=>$newsletter["id"],'news_nome'=>$newsletter["nome"],'news_email'=>$newsletter["email"],'sexo'=>$sexo,'marcar'=>$newsletter["status"]));
						$u++;
					}
				}			
				echo newJSON($arr);
			break;

			case 'setores':
				//Pega os dados do Site
				$modelSite=new ACKsite_Model();
				$dadosSite=$modelSite->dadosSite();
				(int)$limite=$dadosSite["itens_pagina"];
				$idioma=$modelSite->idiomasSite("1");

				//Chama o Model Geral para listar os tópicos
				$modelGeral=new ACKgeral_Model();
				$setores=$modelGeral->listaSetores($itens,$limite);
				if (count($setores) <= $limite){
					$arr['exibir_botao'] = 0;
				} else {
					$arr['exibir_botao'] = 1;
				}				
				$arr['grupo']=array();
				$u=1;			
				foreach ($setores as $setor) {
					if ($u<=$limite) {
						array_push($arr['grupo'],array('id'=>$setor["id"],'titulo'=>$setor["titulo_".$idioma[0]["abreviatura"]],"visivel"=>$setor["visivel"]));
						$u++;
					}
				}			
				echo newJSON($arr);
			break;

			default:
				$arr["status"]="0";
				$arr["mensagem"]="Erro ao carregar lista";
				echo newJSON($arr);
			break;
		}
		
	}
	function visivel($dadosJSON) {		
		postRequest();
		protectedArea();
		openSession();
		verifyTimeSession();
		
		if (!empty($dadosJSON["id_pagina"])) {
			$moduloVerificado=$dadosJSON["id_pagina"];
		} else {
			$moduloVerificado=$dadosJSON["modulo"];
		}
		//Carrega o ID do Módulo
		$modelSite=new ACKsite_Model();
		$moduloVerifica=$modelSite->idModulo($moduloVerificado,false);
		
		$modelUser=new ACKuser_Model();
		if (!$modelUser->userLevel($moduloVerifica, false, "2")) {
			$json['status']=0;
			$json["mensagem"]="Usuário sem permissão";
		} else {
			$dados["resultados"]=array("id"=>$dadosJSON["id"],"visivel"=>$dadosJSON["valor"]);
			dbUpdate($dadosJSON["modulo"], $dados);
			$json['status'] = "1";
		}
		$json['parent'] = $dadosJSON["id"];

		echo newJSON($json);
	}
	function loadDados($dadosJSON) {
		postRequest();
		protectedArea();
		openSession();
		verifyTimeSession();
		// Executa o comando no banco de dados
		global $db;
		$dadosBD["id"]=$dadosJSON["id"];	
		$mysql = $db->prepare('SELECT * FROM '.$dadosJSON["modulo"].' WHERE id=:id');
		$mysql->execute($dadosBD);
		$resultados=$mysql->fetchAll();
		if ($resultados[0]["data"]) {
			$resultados[0]["data"]=convertDate($resultados[0]["data"],"%d-%m-%Y");
		}
		echo newJSON($resultados[0]);
	}
	function ajuda($dadosJSON) {
		postRequest();
		protectedArea();
		openSession();
		verifyTimeSession();
		// Executa o comando no banco de dados
		global $db;
		$identificacao=explode("_",$dadosJSON["campo"]);
		$dadosBD["id"]=$identificacao[1];	
		$dadosBD["tipo"]=$identificacao[0];	
		$mysql = $db->prepare('SELECT * FROM textosack WHERE tipo=:tipo AND id=:id');
		$mysql->execute($dadosBD);
		$resultados=$mysql->fetchAll();
		$json["titulo"]=$resultados[0]["titulo"];
		$json["texto"]=$resultados[0]["texto"];
		echo newJSON($json);
	}
	function idiomasSite() {
		$modelSite=new ACKsite_Model();
		$idiomas["idiomas"]=$modelSite->idiomasSite();
		echo newJSON($idiomas);
	}
	
	function carregar_grupo($dadosJSON) {		
		switch($dadosJSON['id']){
			case '0':
				//Pega os dado das Categorias
				$modelGeral=new ACKgeral_Model();
				$totalItens=$modelGeral->qtdadeItens($dadosJSON['modulo'],"0",true);

				$retorno = array('nome'=>'Todos', 'quantidade'=>$totalItens, 'categoria'=>$dadosJSON['id'], 'modulo'=>$dadosJSON['modulo']);
			break;

			case 'semCategoria':
				//Pega os dado das Categorias
				$modelGeral=new ACKgeral_Model();
				$totalItens=$modelGeral->qtdadeItens($dadosJSON['modulo'],"0",true,false,true);

				$retorno = array('nome'=>'Sem Categoria', 'quantidade'=>$totalItens, 'categoria'=>'semCategoria', 'modulo'=>$dadosJSON['modulo']);
			break;

			default:
				//Pega os dados do Site
				$modelSite=new ACKsite_Model();
				$idiomas=$modelSite->idiomasSite();

				//Pega os dado das Categorias
				$modelGeral=new ACKgeral_Model();
				$totalItens=$modelGeral->qtdadeItens($dadosJSON['modulo'],$dadosJSON['id']);
				$dadosCategoria=$modelGeral->dataCategoria(array("id"=>$dadosJSON['id']));
				$nomeCategoria=$dadosCategoria["titulo_".$idiomas[0]["abreviatura"]];

				$retorno = array('nome'=>$nomeCategoria, 'quantidade'=>$totalItens, 'categoria'=>$dadosJSON['id'], 'modulo'=>$dadosJSON['modulo']);
			break;
		}
		$retorno['status']   = '1';
		$retorno['mensagem'] = 'Itens carregados com sucesso.';
		echo newJSON($retorno);
	}
	
	function dragdrop($dadosJSON) {
		postRequest();
		protectedArea();
		openSession();
		verifyTimeSession();
		if ($dadosJSON["categoria"]=="0") {
			$categoria=false;
		} else {
			$categoria=$dadosJSON["categoria"];
		}
		
		//Pega os dados do Site
		$modelSite=new ACKsite_Model();
		$ordem=$modelSite->ajustaOrdem($dadosJSON["modulo"], $dadosJSON["posicao_nova"], $dadosJSON["posicao_antiga"], $categoria);
		if ($ordem) {
			if ($categoria) {
				$modelGeral=new ACKgeral_Model();
				$item=$modelGeral->dataItem($dadosJSON["modulo"], array("id"=>$dadosJSON["id"]));
				$categorias=unserialize($item["categorias"]);
				if (is_array($categorias)) {
					foreach ($categorias as $idCategoria => $ordem) {
						if ($idCategoria==$categoria) {
							$categorias[$idCategoria]=(int)$dadosJSON["posicao_nova"];
						}
					}
				}
				$dados["resultados"]=array("id"=>$dadosJSON["id"],"categorias"=>serialize($categorias));
				dbUpdate($dadosJSON["modulo"], $dados);
				
			} else {
				$dados["resultados"]=array("id"=>$dadosJSON["id"],"ordem"=>(int)$dadosJSON["posicao_nova"]);
				dbUpdate($dadosJSON["modulo"], $dados);
			}
			$json['status']   = '1';
			$json['mensagem'] = 'Ordem alterada com sucesso.';
		} else {
			$json['status']   = '0';
			$json['mensagem'] = 'Nada foi alterado.';
		}
		echo newJSON($json);
	}	
	function excluir_linha($dadosJSON) {
		postRequest();
		protectedArea();
		openSession();
		verifyTimeSession();
		dbDelete($dadosJSON["tabela"],$dadosJSON["id"],true,false);
		$json["status"]="1";
		$json["mensagem"]="Linha excluida com sucesso!";
		echo newJSON($json);
	}
	function salvar_linha($dadosJSON) {
		postRequest();
		protectedArea();
		openSession();
		verifyTimeSession();
		$dados["resultados"]=$dadosJSON["dadosLinha"];
		$idItem=dbSave($dadosJSON["tipo_tabela"],$dados,true);
		$json["status"]="1";
		$json["mensagem"]="Linha incluída com sucesso!";
		$json["id"]=$idItem;
		echo newJSON($json);
	}
	function editar_linha($dadosJSON) {
		postRequest();
		protectedArea();
		openSession();
		verifyTimeSession();
		$dados["resultados"]=$dadosJSON["dadosLinha"];
		$idItem=dbUpdate($dadosJSON["tipo_tabela"],$dados,true);
		$json["status"]="1";
		$json["mensagem"]="Linha editada com sucesso!";
		$json["id"]=$idItem;
		echo newJSON($json);
	}
}
?>