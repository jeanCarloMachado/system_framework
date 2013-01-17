<?php
	class System_Language implements System_Language_Interface
	{
		/**
		 * nome da sessao
		 */

		const TRANSLATION_FILE_PATH = "includes/languages.php";
		const SESSION_NAME = "lang";
		const DEFAULT_LANG = "pt";

		protected static $_languages = array('pt','en','es');

		/**
		 * retorna a linguagem atual
		 * @return [type] [description]
		 */
		public static function current()
		{
			if(!isset($_SESSION[self::SESSION_NAME])) {
				$_SESSION[self::SESSION_NAME] = self::DEFAULT_LANG;
			}

			return $_SESSION[self::SESSION_NAME];
		}

		/**
		 * seta a linguagem atual
		 */
		public static function setCurrent($lang = null)
		{
			$lang = strtolower($lang);

			if(in_array($lang, self::$_languages)) {
				$_SESSION[self::SESSION_NAME] = $lang;
			}

			$_SESSION[self::SESSION_NAME] = $lang;
		}

		/**
		 * traduz uma string para o idioma setado em setCurrent
		 * @param  string $str [description]
		 * @return [type]      [description]
		 */
		public static function translate(string $str,$lang=null)
		{
			if(!$lang) {
				global $lang_ack;
				$lang = $lang_ack;
			}
			/**
			 * testa se existe uma entrada na tabela languages 
			 * se nao existir retorna o valor normal
			 * @var [type]
			 */
			foreach($lang['pt'] as $columnId => $column) {

				if($column == $str){

					if(!isset($lang[self::current()][$columnId]))
						break;

					return $lang[self::current()][$columnId];
				}
			}

			return $str;
		}
	}
?>