<?php
/*=====================Conexão com o banco de dados MYSQL=====================*/
try {
	global $db_host;
	global $db_name;
	global $db_user;
	global $db_pass;
	$db = new PDO("mysql:host=".$db_host.";dbname=".$db_name, $db_user, $db_pass);
    $db->query("SET NAMES 'utf8'");
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
	$errno="500";
	$erro=$e->getMessage();
	include("includes/View/__erro.php");
	error_log($erro);
	exit;
}
?>