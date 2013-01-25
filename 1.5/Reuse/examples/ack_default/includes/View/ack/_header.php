<?
	$modelUser=new ACKuser_Model();
	$modelSite=new ACKsite_Model();
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <link rel="Shortcut Icon" type="image/ico" href="<? echo $endereco_site; ?>/imagens/ack/favicon.gif" />
    <title><? echo $dadosSite["nome_site"]; ?> | ACK - v5.1 | <? echo $tituloPagina; ?></title>
    
    <link type="text/css" rel="stylesheet" href="<? echo $endereco_site; ?>/css/ack/ack.style.css" />
    <!--[if IE 7]> <link type="text/css" rel="stylesheet" href="<? echo $endereco_site; ?>/css/ack/ack.style_IE7.css" /> <![endif]-->
    <!--[if IE 8]> <link type="text/css" rel="stylesheet" href="<? echo $endereco_site; ?>/css/ack/ack.style_IE8.css" /> <![endif]-->
    <!--[if IE 9]> <link type="text/css" rel="stylesheet" href="<? echo $endereco_site; ?>/css/ack/ack.style_IE9.css" /> <![endif]-->
    
    <script>var siteURL="<? echo $endereco_site; ?>";</script>
    
    <script type="text/javascript" src="<? echo $endereco_site; ?>/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="<? echo $endereco_site; ?>/js/json2.js"></script>
    
    <? if ($autoComplete) { ?>
		<script type="text/javascript" src="<? echo $endereco_site; ?>/plugins/jquery-ui.min.js"></script>
    <? } if ($plugins) { ?>
        <!-- ----- plugin para drag'n drop ----- -->
        <script type="text/javascript" src="<? echo $endereco_site; ?>/plugins/jquery.ui.touch-punch.min.js"></script>
        <script type="text/javascript" src="<? echo $endereco_site; ?>/plugins/jquery.tablednd.0.7.min.js"></script>
        <!-- ----- plugin para upload de arquivos ----- -->
        <script type="text/javascript" src="<? echo $endereco_site; ?>/plugins/uploads/jquery.uploadify.v2.1.4.js"></script>
        <script type="text/javascript" src="<? echo $endereco_site; ?>/plugins/uploads/swfobject.js"></script>
        <script type="text/javascript" src="<? echo $endereco_site; ?>/js/ack/script.ack_uploadFiles.js"></script>
        <!-- ----- plugin para crop de imagens ----- -->
        <link type="text/css" rel="stylesheet" media="all" href="<? echo $endereco_site; ?>/plugins/jCrop/jquery.Jcrop.min.css" />
        <script type="text/javascript" src="<? echo $endereco_site; ?>/plugins/jCrop/jquery.Jcrop.min.js"></script>
        <script type="text/javascript" src="<? echo $endereco_site; ?>/plugins/jCrop/jquery.color.js"></script>
    <? } ?>
    <? if ($dragDrop) {
        ?>
        <script type="text/javascript" src="<? echo $endereco_site; ?>/plugins/jquery-ui.min.js"></script>
        <!-- ----- plugin para drag'n drop ----- -->
        <script type="text/javascript" src="<? echo $endereco_site; ?>/plugins/jquery.ui.touch-punch.min.js"></script>
        <script type="text/javascript" src="<? echo $endereco_site; ?>/plugins/jquery.tablednd.0.7.min.js"></script>
        <?
    }
    ?>
    
    <script type="text/javascript" src="<? echo $endereco_site; ?>/plugins/tiny_mce/jquery.tinymce.js"></script>
    
    <script type="text/javascript" src="<? echo $endereco_site; ?>/js/ack/script.funcoes.ack.js"></script>
    <script type="text/javascript" src="<? echo $endereco_site; ?>/js/ack/script.ack.js"></script>
</head>

<body>
<?
	$dataAcesso=$_SESSION["ultimo_acesso"];
	if (!$dataAcesso) {
		$ultimo_acesso="este é o seu primeiro acesso";	
	} else {
		$data=convertDate($dataAcesso, "%d de %B de %Y às %Hh%M");
		$ultimo_acesso="seu último acesso foi no dia ".$data;	
	}
?>
<div id="wrapper">
    <div class="menuInfo topo">
        <a href="<? echo $endereco_site; ?>/ack" id="logoACK"><img src="<? echo $endereco_site; ?>/imagens/ack/ack_Logo.png" width="76" height="52" alt="oainel ACK" /></a>
        <ul>
            <li><p>Bem-vindo <a href="<? echo $endereco_site; ?>/ack/usuarios/meusDados" title="Editar perfil" class="link"><? echo $dadosUserHeader["nome_tratamento"]; ?></a>, <? echo $ultimo_acesso; ?></p></li>
            <li class="separador">|</li>
            
            <li><a href="<? echo $endereco_site; ?>" target="_blank" title="Visualize o site: <? echo $endereco_site; ?>" class="link">Acessar o site</a></li>
            <li class="separador">|</li>
            
            <li><a href="<? echo $endereco_site; ?>/ack/ajax/sair" title="Sair do painel" class="btn_sair"><em>Sair</em></a></li>
        </ul>
    </div>
    
    <hr />
    
    <div class="wrappeACK">
    	<div class="borda topo"></div>
        
        <div class="wrappeACK-content">
        	
            
            <div id="menuPrincipal">
            	<span class="borda esq"></span>
                
                <ul>
                	<li><a href="<? echo $endereco_site; ?>/ack/dashboard" title="Página inicial">Página inicial</a></li>
                    <li class="separador"></li>
                    
                    <li>
                    	<a href="javascript:void(0);" title="Conteúdo">Conteúdo Site</a>
                        
                        <ul>
                        	<li class="sombraTopo"><!--<img src="<? echo $endereco_site; ?>/imagens/ack/menu_sombraTopo.gif" width="100%" height="5" alt="" />--></li>
							<?
                            $moduloVerificaDestaques=$modelSite->idModulo("destaques",true);
                            $moduloVerificaEnderecos=$modelSite->idModulo("enderecos",true);
                            $moduloVerificaInstitucional=$modelSite->idModulo("institucional");
                            $moduloVerificaProdutosCat=$modelSite->idModulo("categorias_produtos",true);
                            $moduloVerificaProdutos=$modelSite->idModulo("produtos");
                            $moduloVerificaServicosCat=$modelSite->idModulo("categorias_servicos",true);
                            $moduloVerificaServicos=$modelSite->idModulo("servicos");
                            $moduloVerificaNoticiasCat=$modelSite->idModulo("categorias_noticias",true);
                            $moduloVerificaNoticias=$modelSite->idModulo("noticias");
                            $moduloVerificaImprensaCat=$modelSite->idModulo("categorias_imprensa",true);
                            $moduloVerificaImprensa=$modelSite->idModulo("imprensa");
                            $moduloVerificaSetores=$modelSite->idModulo("setores",true);
                            $moduloVerificaContatos=$modelSite->idModulo("contatos",true);
                            $moduloVerificaNewsletter=$modelSite->idModulo("newsletter");
                            $moduloVerificaGeral=$modelSite->idModulo("geral_ack",true);
                            $moduloVerificaMetatags=$modelSite->idModulo("metatags_ack",true);
                            $moduloVerificaUsuarios=$modelSite->idModulo("usuarios_ack",true);
                            $moduloVerificaLogs=$modelSite->idModulo("logs",true);
                            $moduloVerificaModulos=$modelSite->idModulo("modulos",true);

							if ($modelUser->userLevel($moduloVerificaDestaques, false)) { ?>
                                <li><a href="<? echo $endereco_site; ?>/ack/destaques" title="Imagens de destaque">Imagens de destaque</a></li>
                            <? } ?>
							<? if ($modelUser->userLevel($moduloVerificaModulos, false)) { ?>
                                <li><a href="<? echo $endereco_site; ?>/ack/modulos" title="Seções do Site">Seções do Site</a></li>
                            <? } ?>
							<? if ($modelUser->userLevel($moduloVerificaEnderecos, false)) { ?>
                                <li><a href="<? echo $endereco_site; ?>/ack/enderecos" title="Endereços">Endereços</a></li>
                            <? } ?>
							<? if ($modelUser->userLevel($moduloVerificaInstitucional, false)) { ?>
                                <li><a href="<? echo $endereco_site; ?>/ack/institucional" title="Institucional">Institucional</a></li>
                            <? } ?>
							<? if ($modelUser->userLevel($moduloVerificaProdutos, false) or $modelUser->userLevel($moduloVerificaProdutosCat, false)) { ?>
                                <li>
                                    <a href="javascript:void(0);" class="subsub" title="Produtos">Produtos</a>
                                    <ul>
                                        <li class="topo"></li>
										<? if ($modelUser->userLevel($moduloVerificaProdutosCat, false)) { ?>
                                            <li><a href="<? echo $endereco_site; ?>/ack/produtos/categorias" title="Categorias de produtos">Categorias</a></li>
                                        <? } ?>
										<? if ($modelUser->userLevel($moduloVerificaProdutos, false)) { ?>
                                            <li><a href="<? echo $endereco_site; ?>/ack/produtos" title="Lista de produtos">Lista</a></li>
                                        <? } ?>
                                        <li class="fundo"></li>
                                    </ul>
                                </li>
                            <? } ?>
							<? if ($modelUser->userLevel($moduloVerificaServicos, false) or $modelUser->userLevel($moduloVerificaServicosCat, false)) { ?>
                                <li>
                                    <a href="javascript:void(0);" class="subsub" title="Serviços">Serviços</a>
                                    <ul>
                                        <li class="topo"></li>
										<? if ($modelUser->userLevel($moduloVerificaServicosCat, false)) { ?>
                                            <li><a href="<? echo $endereco_site; ?>/ack/servicos/categorias" title="Categorias de serviços">Categorias</a></li>
                                        <? } ?>
										<? if ($modelUser->userLevel($moduloVerificaServicos, false)) { ?>
                                            <li><a href="<? echo $endereco_site; ?>/ack/servicos" title="Lista de serviços">Lista</a></li>
                                        <? } ?>
                                        <li class="fundo"></li>
                                    </ul>
                                </li>
                            <? } ?>
							<? if (($modelUser->userLevel($moduloVerificaNoticias, false)) or ($modelUser->userLevel($moduloVerificaNoticiasCat, false))) { ?>
                                <li>
                                    <a href="javascript:void(0);" class="subsub" title="Notícias">Notícias</a>
                                    <ul>
                                        <li class="topo"></li>
										<? if ($modelUser->userLevel($moduloVerificaNoticiasCat, false)) { ?>
                                            <li><a href="<? echo $endereco_site; ?>/ack/noticias/categorias" title="Grupo de notícias">Grupos</a></li>
                                        <? } ?>
										<? if ($modelUser->userLevel($moduloVerificaNoticias, false)) { ?>
                                            <li><a href="<? echo $endereco_site; ?>/ack/noticias" title="Lista de notícias">Lista</a></li>
                                        <? } ?>
                                        <li class="fundo"></li>
                                    </ul>
                                </li>
                            <? } ?>
							<? if (($modelUser->userLevel($moduloVerificaImprensa, false)) or ($modelUser->userLevel($moduloVerificaImprensaCat, false))) { ?>
                                <li>
                                    <a href="javascript:void(0);" class="subsub" title="Sala de Imprensa">Sala de Imprensa</a>
                                    <ul>
                                        <li class="topo"></li>
										<? if ($modelUser->userLevel($moduloVerificaImprensaCat, false)) { ?>
                                            <li><a href="<? echo $endereco_site; ?>/ack/imprensa/categorias" title="Grupo de sala de imprensa">Grupos</a></li>
                                        <? } ?>
										<? if ($modelUser->userLevel($moduloVerificaImprensa, false)) { ?>
                                            <li><a href="<? echo $endereco_site; ?>/ack/imprensa" title="Lista de sala de imprensa">Lista</a></li>
                                        <? } ?>
                                        <li class="fundo"></li>
                                    </ul>
                                </li>
                            <? } ?>
							<? if ($modelUser->userLevel($moduloVerificaContatos, false) or $modelUser->userLevel($moduloVerificaSetores, false) or $modelUser->userLevel($moduloVerificaNewsletter, false)) { ?>
                                <li>
                                    <a href="javascript:void(0);" class="subsub" title="Contatos">Contatos</a>
                                    <ul>
                                        <li class="topo"></li>
										<? if ($modelUser->userLevel($moduloVerificaNewsletter, false)) { ?>
                                            <li><a href="<? echo $endereco_site; ?>/ack/newsletter" title="Newsletter">Newsletter</a></li>
                                        <? } ?>
										<? if ($modelUser->userLevel($moduloVerificaSetores, false)) { ?>
                                            <li><a href="<? echo $endereco_site; ?>/ack/setores" title="Setores de Contato">Setores de Contato</a></li>
                                        <? } ?>
										<? if ($modelUser->userLevel($moduloVerificaContatos, false)) { ?>
                                            <li><a href="<? echo $endereco_site; ?>/ack/contatos" title="Contatos">Contatos</a></li>
                                        <? } ?>
                                        <li class="fundo"></li>
                                    </ul>
                                </li>
                            <? } ?>
                            <li class="fundo"></li>
                        </ul>
                    </li>
                    <li class="separador"></li>
                    <li>
                    	<a href="javascript:void(0);" title="Configurações">Configurações</a>
                        
                        <ul><!-- AQUI -->
                        	<li class="sombraTopo"><!--<img src="<? echo $endereco_site; ?>/imagens/ack/menu_sombraTopo.gif" width="100%" height="5" alt="" />--></li>
							<? if ($modelUser->userLevel($moduloVerificaGeral, false)) { ?>
                                <li><a href="<? echo $endereco_site; ?>/ack/dadosGerais" title="Dados gerais">Dados gerais</a></li>
                            <? } ?>
							<? if ($modelUser->userLevel($moduloVerificaMetatags, false)) { ?>
                                <li><a href="<? echo $endereco_site; ?>/ack/dadosGerais#metaTags" title="Meta-tags">Meta-tags</a></li>
                            <? } ?>
							<? if ($modelUser->userLevel($moduloVerificaLogs, false)) { ?>
                                <li><a href="<? echo $endereco_site; ?>/ack/logs" title="Logs do sistema">Logs do sistema</a></li>
                            <? } ?>
                        	<li><a href="http://www.google.com.br/analytics" title="Google Analytics" target="_blank">Google Analytics</a></li>
                            <li class="fundo"></li>
                        </ul>
                    </li>
                    <li class="separador"></li>
                    <? if ($modelUser->userLevel($moduloVerificaUsuarios, false)) { ?>
                        <li><a href="<? echo $endereco_site; ?>/ack/usuarios" title="Usuários">Usuários</a></li>
                        <li class="separador"></li>
					<? } ?>
                    <li><a href="<? echo $endereco_site; ?>/ack/textos" title="Ajuda">Ajuda</a></li>
                </ul>
                
            	<span class="borda dir"></span>
            </div><!-- END menuPrincipal -->
            
            <!-- • • • • • • • • • • • • • • • • • • • -->