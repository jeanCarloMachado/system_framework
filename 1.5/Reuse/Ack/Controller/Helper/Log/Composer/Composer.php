<?php
	/**
	 * forma a mensagem de log para o usuário à partir dos parametros passados
	 */
	abstract class Reuse_ACK_Controller_Helper_Log_Composer implements System_Helper_Interface
	{
		const CLASS_PREFIX = "Reuse_ACK_Controller_Helper_Log_Composer_";
		const DEFAULT_CLASS= "Default";
		
		/**
		 * executa o método propriamente dito
		 * @return [type] [description]
		 */
		public static function run(array $params)
		{
			System_Autoloader::setVersion('1.0');

			self::prepareParams($params);

			$className = self::CLASS_PREFIX.$params['className'];

			try {		
				$result = call_user_func(array($className,'run'),$params);
				if(empty($result))
					 throw new Exception("resultado vazio");

			} catch (Exception $e) {
				$className = self::CLASS_PREFIX.self::DEFAULT_CLASS;
				$result = call_user_func(array($className,'run'),$params);
			}

			return $result;
		}

	

		public static function prepareParams(&$params)
		{
			$params['className'] = "";
			$tmp = explode('_',$params['table']);

			foreach($tmp as $element) {
				$params['className'].=ucfirst($element);
			}
		}
	}	
?>