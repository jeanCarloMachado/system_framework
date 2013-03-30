<?php
	class System_Language implements /* System_Language_Interface, */ System_Config_Configurable_Interface
	{
		/**
		 * nome da sessao
		 */

		const SESSION_NAME = "lang";
		const DEFAULT_LANG = "pt";
		private static $translations = null;

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
		public static function translate($str,$lang="pt")
		{
			
			if(!self::$translations) {

				$container = System_Registry::get("container");
				$path = $container->getTranslateFilePath();
				
				require_once $path;
				self::$translations = $trl;
			}
			$trl = self::$translations;
			/**
			 * testa se existe uma entrada na tabela languages 
			 * se nao existir retorna o valor normal
			 * @var [type]
			 */
			
			if(!is_array($trl))
				throw new Exception("array de traduções não setado, ou não linkado corretamente");
			
			if(!empty($trl[System_Language::current()][$str])) {
				return $trl[System_Language::current()][$str];
			}
			
			foreach($trl[$lang] as $columnId => $column) {

				if($column == $str){

					if(!isset($trl[System_Language::current()][$columnId]))
						break;

					return $trl[System_Language::current()][$columnId];
				}
			}
		
			if(System_Language::DEFAULT_LANG != System_Language::Current())	
				return $str." ".System_Language::Current();
			else 
				return $str;
		}
		
		/**
		 * retorna a configuração da classe
		 * @param  [type] $config [description]
		 * @return [type]         [description]
		 */
		public function getConfig()
		{
			$config = System_Config::get();
			return $config->system->controller;
		}
		
		/**
		 * retorna as configurações globais
		 * @param  [type] $config [description]
		 * @return [type]         [description]
		 */
		public function getConfigGlobal()
		{
			$config = System_Config::get();
			return $config->global;
		}
		
		public static function getDefault()
		{
			return "pt";
		}
		
		/**
		 * testa se a linguagem está habilitada
		 * @return boolean
		 */
		public static function hasEnglish()
		{
			$model = new Reuse_Ack_Model_Languages();
			$resultLang = $model->toObject()->onlyAvailable()->get(array("abreviatura"=>"en"));			
			
			$resultLang = reset($resultLang);
			
			if(empty($resultLang))
				return false;
			
			$result=  $resultLang->getVisivel()->getVal();
			return $result;
		}
		
		/**
		 * testa se a linguagem está habilitada
		 * @return boolean
		 */
		public static function hasPortuguese()
		{
				$model = new Reuse_Ack_Model_Languages();
			$resultLang = $model->toObject()->onlyAvailable()->get(array("abreviatura"=>"pt"));			
			
			$resultLang = reset($resultLang);
			
			if(empty($resultLang))
				return false;
			
			$result=  $resultLang->getVisivel()->getVal();
			return $result;
		}
	}
?>