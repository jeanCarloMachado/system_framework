<?php

	abstract class Reuse_ACK_Controller_Helper_Log_Composer_Default implements System_Helper_Interface
	{
		/**
		 * executa o método propriamente dito
		 * @return [type] [description]
		 */
		public static function run(array $params)
		{	
			$affected = (is_array($params['affectedIds'])) ? serialize($params['affectedIds']) : $params['affectedIds'];
			$result = "O usuário ".$params['userName']." ".$params['action']." ".$params['table']." afetando o(s) elemento(s) [".$affected."]";

			return $result;
		}


		
	}	
?>