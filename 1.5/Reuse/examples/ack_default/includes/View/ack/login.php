<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
<link rel="Shortcut Icon" type="image/ico" href="<? echo $endereco_site; ?>/imagens/ack/favicon.gif" />
    <title>ACK - v5</title>
    
    <link type="text/css" rel="stylesheet" href="<? echo $endereco_site; ?>/css/ack/ack.style.login.css" />
    <!--[if IE 7]> <link type="text/css" rel="stylesheet" href="<? echo $endereco_site; ?>/css/ack/ack.style_IE7.css" /> <![endif]-->
    <!--[if IE 8]> <link type="text/css" rel="stylesheet" href="<? echo $endereco_site; ?>/css/ack/ack.style_IE8.css" /> <![endif]-->
    <!--[if IE 9]> <link type="text/css" rel="stylesheet" href="<? echo $endereco_site; ?>/css/ack/ack.style_IE9.css" /> <![endif]-->
    
    <!-- Comum -->
    <script>var siteURL="<? echo $endereco_site; ?>";</script>
    <!-- jQuery -->
    <script type="text/javascript" src="<? echo $endereco_site; ?>/js/jquery-1.7.2.min.js"></script>
    <!-- Plugins -->
    <script type="text/javascript" src="<? echo $endereco_site; ?>/js/json2.js"></script>
    <!-- Script da pagina -->
    <script type="text/javascript" src="<? echo $endereco_site; ?>/js/ack/script.loginACK.js"></script>
</head>

<body>

<div class="boxLogin">
    <img src="<? echo $endereco_site; ?>/imagens/ack/ack_loginLogo.png" width="75" height="45" alt="ACK v4.1" />
    
    <div id="contentForm">
        <div class="formCard formLogin">
            <span></span>
            <div>
            
                <h3 class="titulo">
                	<var>Preencha os campos abaixo para acessar o painel ACK do seu site.</var>
                    <span></span>
                </h3>
                
                <div class="form">
                	<div class="fieldset">
                        <fieldset>
                            <legend><span>Usuário</span></legend>
                            <input type="email" name="usuario" autofocus="autofocus" autocomplete="on" />
                        </fieldset>
                        
                        <fieldset>
                            <legend><span>Senha</span></legend>
                            <input type="password" name="senha" />
                        </fieldset>
                    </div>
                    
                    <div class="boxInfo">
                        <label class="lembrarSenha">
                            <input type="checkbox" name="lembrarSenha" />
                            <span>Mantenha-me conectado</span>
                        </label>
                    </div>
                    
                    <div class="bocBotoes">
                        <button class="esqSenha btn_trocaForm" title="Esqueci minha senha"><span><span>Esqueci minha senha</span></span></button>
                        <input type="button" id="logar" value="Entrar" title="Entrar" />
                    </div><!-- fim footerForm -->
                </div>
                
            </div><!-- fim formLogin -->
            <span></span>
        </div>
        
        <hr /><!-- • •• • • • • • • • ••••• • • • • • • • • •• • -->
        
        <div class="formCard formRECsenha" style="display:none;">
            <span></span>
            <div>
            
                <h3 class="titulo">
                    <var>Esqueceu a senha? Tudo bem, não tem problema.</var>
                    <span></span>
                </h3>
                
                <div class="form">
                    <fieldset>
                        <legend><span>Informe seu e-mail cadastrado</span></legend>
                        <input type="email" name="email" id="emailRec" />
                    </fieldset>
                    
                    <div class="boxInfo">
                        <!--<span class="alerta ok"><em>Uma nova senha foi enviada para o seu e-mail.</em></span>-->
                    </div>
                    
                    <div class="bocBotoes">
                        <button class="volLogin btn_trocaForm" title="Voltar para o login"><span><span>Voltar para o login</span></span></button>
                        <input type="button" id="recuperarSenha" value="Recuperar senha" title="Recuperar senha" />
                    </div><!-- fim footerForm -->
                </div><!-- fim formRECsenha -->
                
            </div>
            <span></span>
        </div>
    </div><!-- END contentForm -->
    
</div><!-- END boxLogin -->

</body>
</html>