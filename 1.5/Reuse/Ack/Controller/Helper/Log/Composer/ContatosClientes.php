<?php

	abstract class Reuse_ACK_Controller_Helper_Log_Composer_ContatosClientes implements System_Helper_Interface
	{
		/**
		 * executa o método propriamente dito
		 * @return [type] [description]
		 */
		public static function run(array $params)
		{
			$result = null;

			if($params['action'] == 'atualizou') {
				$result = "O usuário ".$params['userName']." trocou o status do contato ".$params['affectedIds']." para ".$params['value']['lido'];
			}
				
			return $result;
		}


		
	}	
?>