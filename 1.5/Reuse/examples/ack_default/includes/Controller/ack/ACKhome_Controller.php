<?php
class ACKhome_Controller
{
    function index () {
		openSession();
		if ($_SESSION["email"] and $_SESSION["id"]) {
			verifyTimeSession();
			global $endereco_site;
			header('Location:'.$endereco_site."/ack/dashboard");
		} else {
			if (readCookie("rU") and readCookie("rS")) {
				//Pega dos dados do Cookie
				$usuario=readCookie("rU");
				$senha=base64_decode(readCookie("rS"));
				//Forma o array com usuário e senha
				$userPass=array("usuario"=>$usuario, "senha"=>$senha);
				//Chama o Model com o SQL do banco de dados
				$model=new ACKuser_Model();
				$logado=$model->verifyUser($userPass);
				//Se estiver logado acessa a Dashboard, caso contrário limpa os Cookies e vai pra tela de Login
				if ($logado) {
					global $nome_sessao;
					global $endereco_site;
					global $tempo_sessao;
					$dados=array("resultados"=>array("email"=>$logado["email"], "id"=>$logado["id"], "nome"=>$logado["nome"], "ultimo_acesso"=>$logado["ultimo_acesso"], "expire"=>time()+$tempo_sessao));
					newSession($nome_sessao, $dados);
					$model->updateLastAccess();
					header('Location:'.$endereco_site."/ack/dashboard");
				} else {
					closeSession();
					delCookie("rU");
					delCookie("rS");
					loadView("ack/login");
				}
			} else {
				loadView("ack/login");
			}
		}
	}
}
?>