<?php
	interface System_Language_Interface
	{
		/**
		 * retorna a linguagem atual
		 * @return [type] [description]
		 */
		public static function current();

		/**
		 * seta a linguagem atual
		 */
		public static function setCurrent($lang = null);

		/**
		 * traduz uma string para o idioma setado em setCurrent
		 * @param  string $str [description]
		 * @return [type]      [description]
		 */
		public static function translate(string $str);
	}
?>