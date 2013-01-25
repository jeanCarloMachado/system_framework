<?php

	class PedidosTest extends UnitTestCase
	{	
		private $ajax;
		
		const DEFAULT_SENDER_MAIL = "contato@ikro.com.br";
		const DEFAULT_RECEIVER_MAIL = "jean.machado@bento.ifrs.edu.br";
		
		public function testEmail()
		{
				$pedido = new Pedido();

				$this->ajax = array("acao"=>"novoPedido","passo"=>2,"id"=>"1286"); 
				//	{"acao":"novoPedido","passo":2,"id":"1454"}

				$where = $this->ajax;
				//$where['condicao_pagamento'] = ($where['condicao_pagamento']) ? $where['condicao_pagamento']  : 'asdfjas';
				$where['status'] = 'Aguardando';
				$result = $pedido->update($where,array('id'=>$this->ajax['id']));
				$url = $endereco_site."/pedido/historico";

				/**
				 * envia o email para o usuario
				 */
				//sw($this->ajax);
				//if($this->ajax['email']) {
					global $endereco_site;

					$modelPedido = new Pedido;
					$vars['pedido'] = ($modelPedido->getTree(array("id"=>$this->ajax["id"])));
					$vars['pedido'] = reset($vars['pedido']);

					$email = self::DEFAULT_RECEIVER_MAIL ? self::DEFAULT_RECEIVER_MAIL : $this->ajax["email"];

					System_Autoloader::setVersion("1.5");
						global $endereco_fisico;
						$resultEmail = System_Mail::send($vars,
										"Novo pedido Ikro",
										$endereco_fisico."/includes/View/emails/pedido/email.php",
										$email,
										self::DEFAULT_SENDER_MAIL);
						if(!$resultEmail) 
							dg("error");
						else 
							dg("ok");

		}
	}

?>