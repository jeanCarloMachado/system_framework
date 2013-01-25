<?php
/*====================Vбriaveis gerais do sistema=====================*/
$debug=3; //Sempre em nъmeros
$cache=false;

/*==============Dados de conexгo ao baco de dados do site=============*/
$db_host = "localhost";
$db_user = "root";
$db_pass = "root";
$db_name = "ack_default";

/*=====================Dados de Sessгo e Cookie=======================*/
$nome_sessao="ack";
$diretorio_cookie="/ack_default";
$servidor_cookie=".servidor";
$tempo_sessao=false; //Em segundos | Se for false, o ACK nгo irб expirar 

/*===============Dados para envio e leitura de arquivos===============*/
$endereco_fisico=$_SERVER["DOCUMENT_ROOT"]."/ack_default";
$endereco_site="http://servidor/ack_default";

/*==============Dados para geraзгo e gravaзгo de imagens==============*/
$largura_definida="1920";
$altura_definida="1080";
$qualidade="85";
$tamanho_maximo="4194304";

/*=================Definiзгo do idioma padrгo do site=================*/
setlocale(LC_ALL, "pt_BR", "ptb");

/*===============Caracteres para a conversгo de URL===================*/
$caracteresInvalidos = array("'", '"');
$caracteresConvertidos = array("", "");
?>