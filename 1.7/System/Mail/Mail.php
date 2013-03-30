<?php
	class System_Mail implements System_Mail_Interface,System_Config_Configurable_Interface
	{
		private $_destinatary;
		private $_sender;
		private static $debug = false;
	
		
		public static function setDebug($status)
		{
			self::$debug = $status;
		}
		
		public static function getDebug()
		{
			return self::$debug;
		}
		
		
		public static function send($vars,$subject,$file=null,$destinatary=null,$sender=null) 
		{
			$sm = new System_Mail; 
			$config = $sm->getConfig();
			/**
			 * se não foi passado o destinatário
			 * pega o default da aplicacao
			 */
			$destinatary = ($destinatary) ? $destinatary : $config->destinatary;
			 
			/**
			 * se não foi passado o emissor
			 * pega o default da aplicacao
			 */
			$sender = ($sender) ? $sender : $config->sender;
			
			/**
			 * se não foi passado o caminho do arquivo
			 * pega o default da aplicacao
			 */

			$file = ($file) ? $file : $config->layout->path;



			//Informações sobre quebra de linha para o Header do E-mail
			if(PHP_OS == "Linux") $quebra_linha = "\n"; //Se for Linux
			elseif(PHP_OS == "WINNT") $quebra_linha = "\r\n"; // Se for Windows
			elseif(PHP_OS == "Darwin") $quebra_linha = "\n"; // Se for MacOS
			else die("Este script nao esta preparado para funcionar com o sistema operacional de seu servidor");

			//Define o cabeçalho do e-mail
			$headers = "MIME-Version: 1.1".$quebra_linha;
			$headers .= "Content-type: text/html; charset=utf-8".$quebra_linha;
			$headers .= "From: ".$sender.$quebra_linha;
			$headers .= "Return-Path: ".$sender.$quebra_linha;
			$headers .= "Reply-To: ".$sender.$quebra_linha;
			
			/**
			 * envia o email
			 */
			$content = self::loadMail($file,$vars);
		
			if(self::getDebug()) {
				dg($content);
			} else
				return mail($destinatary, $subject,$content , $headers);
		}

		private static function loadMail($file,$vars = array()) 
		{

			$config = System_Config::get();
			$config =  $config->system->mail;
			
			ob_start(); // start buffer
				extract($vars);
				global $endereco_site;
				/**
				 * adiciona o cabeçalho
				 */
				//require_once $config->layout->header;
				require_once $file;
				//require_once $config->layout->footer;

			$content = ob_get_contents(); // assign buffer contents to variable
			ob_end_clean(); // end buffer and remove buffer contents

			return $content;
		}

		/**
		 * retorna a configuração da classe 
		 * @param  [type] $config [description]
		 * @return [type]         [description]
		 */
		public function getConfig() 
		{
			$config = System_Config::get();
			
			return $config->system->mail;
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
	}		
?>