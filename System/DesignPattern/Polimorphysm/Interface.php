<?php
	/**
	 * interface para objetos polimórficos
	 */
	interface System_DesignPattern_Polimorphysm_Interface
	{
		/**
		 * é passado argumentos a classe polimorfica
		 * a qual de acordo com esses argumentos intancia
		 * a classe necessária
		 * @param  array  $params [description]
		 * @return [type]         [description]
		 */
		public static function getInstance($params);
	}
?>