<?php
/*====================Variaveis gerais do sistema=====================*/
$debug=3; //Sempre em n�meros
$cache=false;

/*==============Dados de conexao ao baco de dados do site=============*/
$db_host = "localhost";
$db_user = "giulian_tst";
$db_pass = "123pass";
$db_name = "giulian_tst";

/*=====================Dados de Sessao e Cookie=======================*/
$nome_sessao="giulian";
$diretorio_cookie="/clientes/giulian";
$servidor_cookie=".icub.com.br";
$tempo_sessao=false; //Em segundos | Se for false, o ACK n�o ir� expirar 

/*===============Dados para envio e leitura de arquivos===============*/
$endereco_fisico=$_SERVER["DOCUMENT_ROOT"]."/clientes/giulian";
$endereco_site="http://www.icub.com.br/clientes/giulian";

/*==============Dados para geracao e gravacao de imagens==============*/
$largura_definida="1920";
$altura_definida="1080";
$qualidade="95";
$tamanho_maximo="4194304";

/*=================Definicao do idioma padrao do site=================*/
setlocale(LC_ALL, "pt_BR", "ptb");

/*===============Caracteres para a conversao de URL===================*/
$caracteresInvalidos = array("'", '"');
$caracteresConvertidos = array("", "");

/**
 *  distancia máxima em km 
 *  para ser considerado mudança local
 */
define("MAX_LOC_DISTANCE",99);

?>