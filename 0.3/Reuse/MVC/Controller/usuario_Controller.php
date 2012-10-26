<?php
	class usuario_controller extends Controller 
	{
		/**
		 * instancia de System_Auth
		 * @var [type]
		 */
		protected $_auth;
		/**
		 * inicializa a aplicacao
		 * @return [type] [description]
		 */
		public function preDispatch()
		{

			global $endereco_site;
			/**
			 * faz autenticação automática em todos os métodos
			 */
			$this->_authenticator->enableAuthentication();
			$this->_authenticator->setErrorPath("$endereco_site/home/");
			/**
			 * seta as funções que não necessitarão autenticação
			 */
			$this->_authenticator->setExceptions(array('login','recsenha_ajax'));

			$this->_auth = System_Auth::Factory('Default',null);

		}

		public function login() 
		{
			if($this->ajax['acao']=="login") {
				global $endereco_site;
				$authenticated = $this->_auth->authenticate($this->ajax['usuario'],$this->ajax['senha']);
				if($authenticated) {
					$result =  array('status'=>1,'url'=>"$endereco_site/usuario/editar/");			
				} else {
					$result =  array('status'=>0,'url'=>"$endereco_site/home/");	
				}
			}
			echo newJSON($result);
		}	

		/**
		 * faz logoff no sistema
		 * @return [type] [description]
		 */
		public function logoff() 
		{
			global $endereco_site;

			if($this->ajax['acao'] == 'logoff') {
			        	$this->_auth->logoff();
	        			$retorno = array('status'=>1,'url'=> $endereco_site.'/home/');	 
			} else {
				$retorno = array('status'=>0,'mensagem'=> 'Você não está logado.');	
			}

			echo newJSON($retorno);
		}

		public function editar() 
		{
			$cidade = new Cidade;
			$usuario = new Usuario;

			//PEGA A SESSAO DO USUARIO
			$user = ($this->_auth->getUser());
			//PEGA O USUARIO DO BANCO PELO ID
			$result = $usuario->get(array('id'=>$user['id']));
			$resultCidade = $cidade->getParents(array('id'=>$result[0]['cidade_id']));


			$result[0]['cidade'] = $resultCidade[0]['nome'];
			$result[0]['estado'] = $resultCidade[0]['estado'];
			$result[0]['cep'] = $resultCidade[0]['cep'];
			$result[0]['pais'] = $resultCidade[0]['pais'];
		
			$data['user'] = $result[0];

			loadView('restrito/usuario/editar',$data);
		}

		public function editar_ajax() 
		{

			$usuario = new Usuario();
			//PEGA A SESSAO DO USUARIO
			$user = ($this->_auth->getUser());

			//SALVA A EDICAO
			if($this->ajax['acao'] == 'alteraPerfil') {

				unset($this->ajax['acao']);
				$set = $this->ajax;
				
				if(strlen($set['novaSenha'])>1)	{

					$set['senha_responsavel'] = $set['novaSenha'];
				}

				unset($set['novaSenha']);
				unset($set['novaSenha2']);
				//RENOMEIA O CAMPO NOME FANTASIA
				$set['nome_fantasia'] = $set['nomeFantasia'];
				unset( $set['nomeFantasia']);
				//DA UPDATE DOS DADOS
				$usuario->updateUser($set,array('id'=>$user['id']));
				
				echo newJSON(array('status'=>0,'mensagem'=>'Dados salvos com sucesso!'));
			}
		}

		/**
		 * funções genéricas de ajax
		 * @return [type] [description]
		 */
		public function recsenha_ajax()
		{
			if($this->ajax['acao']=='rec_senha') {
				/**
				 * recupera a senha 
				 */
				$user = new Usuario;
				$userResult = $user->get(array('email_responsavel'=>$this->ajax["email"]));

				if(!empty($userResult[0])) {

					$novaSenha = geraSenha();
					$user->updatePassword(md5($novaSenha),array('id'=>$userResult[0]['id']));
					
					$vars = &$userResult[0];
					$vars['assunto'] = "Recuperação de senha.";
					$vars['nova_senha'] = $novaSenha;

					global $destinatary;
					$result = enviaEmail($destinatary, 'rec_senha', $vars, $remetente=false);

					if(!$result)
						$result = array('status'=>0,'mensagem'=>"Falha no envio.");
					else
						$result = array('status'=>1,'mensagem'=>"Um email foi enviado para sua conta contendo a nova senha.");

					echo newJSON($result);	

				} else {
					echo newJSON(array('status'=>0,'mensagem'=>'Usuário não existe.'));	
				}
				
			}
			
		}
	}
?>