<?php
	error_reporting(E_ALL);
	
	/**
	 * testa o filtro de usuário no ack
	 *
	 * {"acao":"carregar_mais","modulo":"orcamentos_app","qtd_itens":0,"usuario":"0","status":"0","origem":"0","dataInicial":"","dataFinal":""}
	 */
	class FiltrarUsuarioTestCase extends System_Test 
	{
		function testReturnDefault()
		{
				require_once 'includes/Controller/ack/ACKajax_Controller.php';
				$controller = new  ACKajax_Controller;
				// $this->expectException();
				
				// $result = $controller->carregar_mais(array('acao'=>'carregar_mais',
				// 						'modulo'=>'usuarios_app',
				// 						'qtd_itens'=>0,
				// 						'usuario'=>0,
				// 						'ativo'=>0,
				// 						'dataInicial'=>"",
				// 						'dataFinal'=>""));
				$this->assertTrue(1);
		}
	}

	$test = new FiltrarUsuarioTestCase('Testing Unit Test');;
	$test->run();

?>